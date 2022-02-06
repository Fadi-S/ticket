<?php

namespace App\Http\Livewire;

use App\Events\TicketReserved;
use App\Helpers\GenerateRandomString;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class MakeReservation extends Component
{
    public $event;
    public Collection $users;

    public $search = '';

    public bool $redirectAfterReservation = true;

    public User $user;

    protected $listeners = [
        'set:event' => 'setEvent',
        'reserve' => 'save',
        'confirm' => 'confirm',
        'user-created' => 'userCreated',
    ];

    protected function getMessages()
    {
        return [
            'users.required' => __('You must choose users'),
            'users.filled' => __('You must choose users'),
            'event.exists' => __('You must choose an event'),
        ];
    }

    public function mount()
    {
        $this->users = collect();

        $this->redirectAfterReservation = false; // !auth()->user()->can('tickets.view');

        if (auth()->user()->isUser()) {
            $this->users->push(auth()->user());
        }
    }

    public function setEvent($id)
    {
        $this->event = $id;
    }

    public function render()
    {
        return view('livewire.make-reservation', [
            'usersSearched' => $this->getSearchedUsers(),
        ])->layout('components.master');
    }

    public function confirm($confirmation)
    {
        session()->flash($confirmation, true);

        $this->save();
    }

    public function save()
    {
        $this->validate([
            'users' => [
                'required',
                'filled',
            ],
        ]);

        $event = Event::query()->withReservationsCount()->published()->find($this->event);

        if (!$event) {
            return $this->failWithErrorMessage('This event does not exist');
        }

        $users = User::query()
            ->when(!auth()->user()->can('tickets.view'), fn($q) => $q->hasFriends())
            ->whereIn('id', $this->users->pluck('id'))
            ->with('roles')
            ->get();

        \DB::beginTransaction();

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'reserved_at' => now(),
            'reserved_by' => \Auth::id(),
            'secret' => (new GenerateRandomString)->handle(),
        ]);
        $ticket->setRelation('event', $event);

        $reservations = [];
        foreach ($users as $user) {
//            if (!$user->isSignedIn() && !auth()->user()->can('tickets.view') && !$user->isFriendsWith(auth()->user(), false)) {
//                return $this->failWithErrorMessage("You are not friends with $user->name");
//            }

            $output = $user->canReserveIn($event);

            if (is_null($output)) {
                return $this->failWithErrorMessage("Something went wrong for $user->name");
            }

            if ($output->waitingForConfirmation()) {
                return $this->waitForConfirmation($output->message(), $output->getConfirmationName());
            }

            if ($output->hasMessage()) {
                return $this->failWithErrorMessage($output->message());
            }

            $reservations[] = $user->reserveIn($ticket, $output, false);
        }

        Reservation::insert($reservations);

        \DB::commit();

        TicketReserved::dispatch($ticket);

        $this->handleSuccess($event);

        return true;
    }

    private function handleSuccess($event)
    {
        $message = __("Reservation made successfully");

        if($this->redirectAfterReservation) {
            flash()->success($message);
            redirect("tickets?event=$event->id");
        } else {
            $this->dispatchBrowserEvent('open', [
                'title' => __('Success'),
                'message' => $message,
                'type' => 'success',
            ]);

            $this->dispatchBrowserEvent('reservation');

            $this->event = null;
        }
    }

    public function clearForm()
    {
        $this->users = collect();
        $this->event = null;
        $this->search = '';
        $this->dispatchBrowserEvent('reservation');
    }

    public function getSearchedUsers()
    {
        return User::searchDatabase($this->search)
            ->when(!auth()->user()->can('tickets.view'), fn($query) => $query->hasFriends())
            ->limit(8)
            ->get();
    }

    public function removeUser($id)
    {
        $this->users = $this->users->reject(
            fn($currentUser) => $currentUser['id'] == $id
        );
    }

    public function toggleUser($userId)
    {
        if(! is_numeric($userId)) {
            $user = $userId;
        }

        if(!$this->users->contains('id', $userId)) {
            $maxUsers = (int) config('settings.max_users_per_reservation');

            if($maxUsers && $this->users->count() >= $maxUsers) {
                return $this->failWithErrorMessage(
                    __('You can not add more than :count users', ['count' => $maxUsers]),
                    __("Can't add more users"),
                );
            }

            $this->users->push(
                $user ??
                User::query()
                    ->select(['id', 'name', 'arabic_name', 'national_id', 'church_id'])
                    ->find($userId)
            );
        } else {
            $this->removeUser($userId);
        }
    }

    public function userCreated(User $user)
    {
        $this->dispatchBrowserEvent('closeuser');

        $this->users->push($user);
    }

    private function failWithErrorMessage($message, $title=null) : bool
    {
        \DB::rollBack();

        $title ??= __("Couldn't reserve in this event");

        session()->flash('error', $message);
        $this->dispatchBrowserEvent('open', [
            'title' => $title,
            'message' => $message,
            'type' => 'error',
        ]);

        return false;
    }

    private function waitForConfirmation($message, $confirmation) : bool
    {
        \DB::rollBack();

        session()->flash('error', $message);
        $this->dispatchBrowserEvent('open-admin-confirmation', [
            'title' => __("Are you sure?"),
            'message' => $message,
            'type' => $confirmation,
        ]);

        return false;
    }
}
