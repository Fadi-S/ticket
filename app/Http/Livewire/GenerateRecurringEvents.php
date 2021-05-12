<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GenerateRecurringEvents extends Component
{
    public $start;
    public $end;
    public $publish_at;

    protected $rules = [
        'start' => 'required',
        'end' => 'required|after:start',
        'publish_at' => 'required',
    ];

    public function render()
    {
        return view('livewire.generate-recurring-events');
    }

    public function generate()
    {
        $this->dispatchBrowserEvent('message', [
            'level' => 'success',
            'message' => __('Events generated successfully'),
            'important' => false,
        ]);

        $this->dispatchBrowserEvent('close');
    }
}
