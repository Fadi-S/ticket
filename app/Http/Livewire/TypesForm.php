<?php

namespace App\Http\Livewire;

use App\Models\EventType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TypesForm extends Component
{
    use AuthorizesRequests;

    public EventType $type;
    public bool $show;
    public bool $isCreate;

    public function mount()
    {
        $this->authorize('create');

        $this->isCreate = ! isset($this->type);

        $this->type ??= new EventType();
        $this->show = !! $this->type->show;
    }

    public function render()
    {
        return view('livewire.types-form');
    }

    public function save()
    {
        $this->validate();

        $this->type->show = $this->show;
        $this->type->save();

        session()->flash('success', __('Type Saved Successfully'));
    }

    public function rules()
    {
        $id = $this->type->id;

        return [
            'type.name' => ['required'],
            'type.arabic_name' => ['required'],
            'type.plural_name' => ['required'],
            'type.url' => [
                'required',
                Rule::unique('event_types', 'url')->ignore($id),
            ],
            'type.max_reservations' => ['required', 'min:1'],
            'show' => ['required', 'boolean'],
        ];
    }
}