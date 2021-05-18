<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class GenerateRecurringEvents extends Component
{
    public $start;
    public $end;
    public $publish_at;

    public $type;

    protected $rules = [
        'start' => 'required|date|date_format:Y-m-d',
        'end' => 'required|date|after:start|date_format:Y-m-d',
        'publish_at' => 'required|date|date_format:Y-m-d',
    ];

    public function render()
    {
        return view('livewire.generate-recurring-events');
    }

    public function generate()
    {
        $this->validate();

        Artisan::call("events:create --start=$this->start --end=$this->end --publish=$this->publish_at --type=$this->type");

        $this->dispatchBrowserEvent('message', [
            'level' => 'success',
            'message' => __('Events generated successfully'),
            'important' => false,
        ]);

        $this->dispatchBrowserEvent('close');
    }
}
