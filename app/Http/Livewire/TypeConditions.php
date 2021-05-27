<?php

namespace App\Http\Livewire;

use App\Models\Condition;
use App\Models\EventType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class TypeConditions extends Component
{
    use AuthorizesRequests;
    public EventType $type;

    public $conditions;
    public $appliedConditions;

    public function mount()
    {
        $this->authorize('create');

        $this->getConditions();
    }

    public function render()
    {
        return view('livewire.type-conditions');
    }

    public function toggle(Condition $condition)
    {
        if($condition['required']) {
            return;
        }

        $this->type->toggleCondition($condition);

        $this->getConditions();
    }

    private function getConditions()
    {
        $conditions = Condition::all();
        $applied = $this->type
            ->conditions()
            ->where('church_id', '=', auth()->user()->church_id)
            ->get();

        $conditions = $conditions->filter(
            fn($condition) => $applied->where('id', $condition->id)->isEmpty()
        );

        $this->conditions = $conditions->groupBy('priority')
            ->toArray();
        $this->appliedConditions = $applied->groupBy('priority')
            ->toArray();
    }
}
