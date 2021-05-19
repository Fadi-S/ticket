<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\Period;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PeriodsTable extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
        $this->authorize('viewAny', Event::class);
    }

    public function render()
    {
        return view('livewire.periods-table', [
            'periods' => Period::where('end', '>=', now())->orderBy('start')->paginate(15)
        ])->layout('components.master');
    }
}
