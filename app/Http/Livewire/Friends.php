<?php

namespace App\Http\Livewire;

use App\Models\User\User;
use Livewire\Component;

class Friends extends Component
{
    public bool $showModal = false;

    public $search = '';

    public function render()
    {
        return view('livewire.friends', [
            'friends' => auth()->user()->friends()->paginate(15),
            'requests' => auth()->user()
                ->friendships()
                ->with('sender')
                ->where([
                    ['sender_id', '<>', auth()->id()],
                    ['confirmed_at', null]
                ])
                ->get(),
        ])->layout('components.master');
    }

    public function openFriendModal()
    {
        $this->showModal = true;
    }

    public function addFriend()
    {
        $user = User::strictSearch($this->search)->first();

        if(!$user) {
            session()->flash('error', __('User not found'));

            return;
        }

        if($user->isSignedIn())
        {
            session()->flash('error', __("You can't add yourself"));

            return;
        }

        $friendship = auth()->user()
            ->friendships()
            ->whereHas('users', fn($query)=>$query->where('id', $user->id))
            ->first();

        if($friendship)
        {
            if($friendship->confirmed_at) {
                session()->flash('error', __('You are already friends!'));
            } else {
                if($friendship->sender_id === $user->id) {
                    $this->confirmFriend($user);

                    $this->dispatchBrowserEvent('close');
                }

                $this->dispatchBrowserEvent('message', [
                    'level' => 'success',
                    'message' => __('Friend Request Sent'),
                    'important' => false,
                ]);

                $this->dispatchBrowserEvent('close');
            }

            return;
        }

        $this->dispatchBrowserEvent('message', [
            'level' => 'success',
            'message' => __('Friend Request Sent'),
            'important' => false,
        ]);

        auth()->user()->addFriend($user);

        $this->dispatchBrowserEvent('close');

        $this->search = '';
    }

    public function confirmFriend(User $user)
    {
        auth()->user()->confirmFriend($user);

        $this->dispatchBrowserEvent('message', [
            'level' => 'success',
            'message' => __('Friend added'),
            'important' => false,
        ]);
    }
}
