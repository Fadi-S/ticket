<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;

class PeriodForm extends Component
{
    use AuthorizesRequests;

    public Period $period;
    public bool $isCreate;
    public $start;
    public $end;

    public function mount()
    {
        $this->authorize('create', Event::class);
        $this->isCreate = ! isset($this->period);

        if(!$this->isCreate) {
            $this->start = Carbon::parse($this->period->start)->format('Y-m-d');
            $this->end = Carbon::parse($this->period->end)->format('Y-m-d');
        }

        $this->period ??= new Period;
    }

    public function render()
    {
        return view('livewire.period-form', [
            'types' => [0 => '-'] + EventType::shown()->pluck('arabic_name', 'id')->toArray()
        ])->layout('components.master');
    }

    public function updated($field, $value)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        $this->validate();

        $this->period->start = Carbon::parse($this->start)->startOfDay();
        $this->period->end = Carbon::parse($this->end)->endOfDay();
        $this->period->save();

        \Cache::tags('periods')->flush();
        \Cache::tags('ticket.users')->flush();

        session()->flash('success', __('Period Saved Successfully'));

        if($this->isCreate) {
            $this->period = new Period;
        }
    }

    public function rules()
    {
        return [
            'period.name' => 'required',
            'period.type_id' => 'required|exists:event_types,id',
            'start' => 'required',
            'end' => 'required',
        ];
    }
}
