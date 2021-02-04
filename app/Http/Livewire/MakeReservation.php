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

    public function mount()
    {
        $this->users = collect();

        if(auth()->user()->isUser()) {
            $this->users->push(auth()->user());
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
        $event = Event::findOrFail($this->event)->specific();

        $users = User::whereIn('id', $this->users->pluck('id'))->get();

        if(!auth()->user()->isAdmin()) {
            if ($users->count() > $event->reservations_left) {
                flash()->error('There is not enough space');

                return redirect("reservations/create");
            }
        }

        $users->each(function ($user) use($event) {
            $output = $user->canReserveIn($event);

            if(is_null($output)) {
                flash()->error("Something went wrong for $user->name");

                return;
            }

            if($output->hasMessage())
                flash()->error($output->message());
        });

        if(flash()->messages->isNotEmpty())
            return redirect("reservations/create");

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'reserved_at' => Carbon::now(),
            'reserved_by' => \Auth::id(),
            'secret' => (new GenerateRandomString)->handle(),
        ]);

        TicketReserved::dispatch();

        $users->each->reserveIn($ticket);

        if(flash()->messages->isEmpty()) {
            flash()->success("Reservation(s) made successfully");
            return redirect("tickets?event=$event->id");
        }

        return redirect("reservations/create");
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
}
