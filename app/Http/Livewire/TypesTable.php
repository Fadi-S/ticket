<?php

namespace App\Http\Livewire;

use App\Models\EventType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class TypesTable extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
        $this->authorize('conditions.edit');
    }

    public function render()
    {
        return view('livewire.types-table', [
            'types' => EventType::query()
                ->orderBy('show', 'DESC')
                ->get(),
        ]);
    }
}
