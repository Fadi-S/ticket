<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Friends extends Component
{
    public function render()
    {
        return view('livewire.friends', [
            'friends' => auth()->user()->friends()->paginate(15),
            'requests' => auth()->user()
                ->friends(true)->get(),
        ])->layout('components.master');
    }
}
