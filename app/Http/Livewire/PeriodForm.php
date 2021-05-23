<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PeriodForm extends Component
{
    use AuthorizesRequests;

    public Period $period;
    public bool $isCreate;
    public $start;
    public $end;

    public array $types = [];

    public function mount()
    {
        $this->authorize('create', Event::class);
        $this->isCreate = ! isset($this->period);

        if(!$this->isCreate) {
            $this->start = $this->period->start->format('d/m/Y');
            $this->end = $this->period->end->format('d/m/Y');
        }

        $this->period ??= new Period;
    }

    public function render()
    {
        return view('livewire.period-form')
            ->layout('components.master');
    }

    public function updated($field, $value)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        $this->validate();

        $this->period->start = Carbon::createFromFormat("Y-m-d", $this->start);
        $this->period->end = Carbon::createFromFormat("Y-m-d", $this->end);
        $this->period->save();

        session()->flash('success', __('Period Saved Successfully'));

        if($this->isCreate) {
            $this->period = new Period;
        }
    }

    public function rules()
    {
        return [
            'period.name' => 'required',
            'start' => 'required',
            'end' => 'required',
        ];
    }
}
