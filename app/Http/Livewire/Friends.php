<?php

namespace App\Http\Livewire;

use App\Models\User\User;
use Livewire\Component;

class Friends extends Component
{
    public bool $showModal = false;

    public $search;

    public function render()
    {
        return view('livewire.friends', [
            'friends' => auth()->user()->friends()->paginate(15),
            'requests' => auth()->user()
                ->friends(true)->get(),
        ])->layout('components.master');
    }

    public function openFriendModal()
    {
        $this->showModal = true;
    }

    public function addFriend()
    {
        $user = User::strictSearch($this->search)->first();

        $this->dispatchBrowserEvent('close');

        $this->search = '';
    }
}
