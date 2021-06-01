<?php

namespace App\Http\Livewire;

use App\Events\TicketReserved;
use App\Events\UserRegistered;
use App\Helpers\GenerateRandomString;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class MakeReservation extends Component
{
    public $event;
    public Collection $users;

    public $search = '';

    public User $user;

    protected $listeners = [
        'set:event' => 'setEvent',
        'user-created' => 'userCreated',
    ];

    protected function getMessages()
    {
        return [
            'users.required' =>  __('You must choose users'),
            'users.filled' =>  __('You must choose users'),
            'event.exists' =>  __('You must choose an event'),
        ];
    }

    public function mount()
    {
        $this->users = collect();

        if(auth()->user()->isUser() || auth()->user()->isDeacon()) {
            $this->users->push(auth()->user());
        }
    }

    public function setEvent($id)
    {
        $this->event = $id;

        if($this->users->isNotEmpty()) {
            $this->save();
        }
    }

    public function render()
    {
        return view('livewire.make-reservation', [
            'usersSearched' => $this->getSearchedUsers(),
        ])->layout('components.master');
    }

    public function save()
    {
        $this->validate([
            'users' => [
                'required',
                'filled',
            ],
            'event' => [
                'exists:events,id'
            ]
        ]);

        $event = Event::published()->find($this->event);

        if(!$event) {
            return $this->failWithErrorMessage('This event does not exist');
        }

        $event = $event->specific();

        $users = User::whereIn('id', $this->users->pluck('id'))->get();

        $users->each(function ($user) use($event) {

            if(!$user->isSignedIn() && !auth()->user()->can('tickets.view') && !$user->isFriendsWith(auth()->user(), false)) {
                return $this->failWithErrorMessage("You are not friends with $user->name");
            }

            $output = $user->canReserveIn($event);

            if(is_null($output)) {
                return $this->failWithErrorMessage("Something went wrong for $user->name");
            }

            if($output->hasMessage()) {
                $this->failWithErrorMessage($output->message());
            }
        });

        if(session()->has('error'))
            return false;

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'reserved_at' => Carbon::now(),
            'reserved_by' => \Auth::id(),
            'secret' => (new GenerateRandomString)->handle(),
        ]);

        $users->each->reserveIn($ticket);

        TicketReserved::dispatch($ticket);

        flash()->success(Str::plural('Reservation', $users->count()) . " made successfully");
        redirect("tickets?event=$event->id");

        return true;
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
            fn ($currentUser) => $currentUser['id'] == $id
        );
    }

    public function toggleUser(User $user)
    {
        if(!$this->users->containsStrict('id', $user->id)) {
            $this->users->push($user);
        } else {
            $this->removeUser($user->id);
        }
    }

    public function userCreated(User $user)
    {
        $this->dispatchBrowserEvent('closeuser');

        $this->users->push($user);
    }

    private function failWithErrorMessage($message) : bool
    {
        session()->flash('error', $message);
        $this->dispatchBrowserEvent('open', $message);

        return false;
    }
}
