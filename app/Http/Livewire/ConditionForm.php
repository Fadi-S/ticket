<?php

namespace App\Http\Livewire;

use App\Models\Condition;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ConditionForm extends Component
{
    use AuthorizesRequests;

    public Condition $condition;
    public bool $required = false;

    public function mount()
    {
        $this->authorize('create', Condition::class);

        $this->condition ??= new Condition();
        $this->required = $this->condition->required;
    }

    public function render()
    {
        return view('livewire.condition-form');
    }

    public function save()
    {
        $this->validate();

        $this->condition->required = $this->required;
        $this->condition->save();

        session()->flash('success', __('Condition Saved Successfully'));
    }

    public function rules()
    {
        return [
            'condition.name' => ['required', 'unique:conditions,name'],
            'condition.path' => ['required', 'unique:conditions,path'],
            'condition.priority' => ['required', 'numeric'],
            'required' => ['required', 'boolean'],
        ];
    }
}
