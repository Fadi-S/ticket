<?php

namespace App\Http\Livewire;

use App\Models\Condition;
use App\Rules\ConditionExists;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ConditionForm extends Component
{
    use AuthorizesRequests;

    public Condition $condition;
    public bool $required = false;
    public bool $isCreate;

    public function mount()
    {
        $this->authorize('create', Condition::class);

        $this->isCreate = ! isset($this->condition);
        $this->condition ??= new Condition();
        $this->required = !! $this->condition->required;
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

        if($this->isCreate) {
            $this->condition = new Condition;
            $this->required = false;
        }
    }

    public function rules()
    {
        $id = $this->condition->id;

        return [
            'condition.name' => [
                'required',
                Rule::unique('conditions', 'name')->ignore($id),
            ],
            'condition.path' => [
                'required',
                Rule::unique('conditions', 'path')->ignore($id),
                new ConditionExists(),
            ],
            'condition.priority' => ['required', 'numeric'],
            'required' => ['required', 'boolean'],
        ];
    }
}
