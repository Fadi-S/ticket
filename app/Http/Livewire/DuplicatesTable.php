<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DuplicatesTable extends Component
{

    public function mount()
    {
        if(!auth()->user()->can('logins.view')) {
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.duplicates-table')
            ->layout('components.master');
    }
}
