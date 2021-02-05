<?php

namespace App\Http\Livewire;

use App\Events\TicketReserved;
use App\Helpers\GenerateRandomString;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class MakeReservation extends Component
{
    public $event;
    public Collection $users;

    public $search = '';
    public bool $searching = false;

    protected $listeners = [
        'set:event' => 'setEvent',
        'user-created' => 'userCreated'
    ];

    public function mount()
    {
        $this->users = collect();

        if(auth()->user()->isUser()) {
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

        $event = Event::findOrFail($this->event)->specific();

        $users = User::whereIn('id', $this->users->pluck('id'))->get();

        $users->each(function ($user) use($event) {
            $output = $user->canReserveIn($event);

            if(is_null($output)) {
                session()->flash('error', "Something went wrong for $user->name");
                $this->dispatchBrowserEvent('open', "Something went wrong for $user->name");

                return;
            }

            if($output->hasMessage()) {
                session()->flash('error', $output->message());
                $this->dispatchBrowserEvent('open', $output->message());
            }
        });

        if(session()->has('error'))
            return;

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'reserved_at' => Carbon::now(),
            'reserved_by' => \Auth::id(),
            'secret' => (new GenerateRandomString)->handle(),
        ]);

        $users->each->reserveIn($ticket);

        TicketReserved::dispatch();

        flash()->success("Reservation(s) made successfully");
        redirect("tickets?event=$event->id");
    }

    public function getSearchedUsers()
    {
        return User::search($this->search)
            ->when(auth()->user()->isUser(), fn($query) => $query->hasFriends())
            ->limit(5)
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
}
