<?php

namespace App\Http\Livewire;

use App\Models\Event;

use App\Models\Mass;
use App\Models\Template;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class TemplatesForm extends Component
{
    use AuthorizesRequests;

    public Template $template;
    public bool $isCreate;

    public $start;
    public $end;
    public $type_id;

    protected $queryString = [
        'type_id' => ['except' => null]
    ];

    public function mount()
    {
        $this->authorize('create', Event::class);

        $this->isCreate = ! isset($this->template);

        $this->template ??= new Template;

        if(!$this->isCreate) {
            $this->template->overload = 100 * $this->template->overload;

            $this->start = $this->template->start->format('H:i');
            $this->end = $this->template->end->format('H:i');
        }

    }

    public function render()
    {
        return view('livewire.templates-form')
            ->layout('components.master');
    }

    public function updated($field, $value)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        $this->validate();

        $this->template->start = $this->start;
        $this->template->end = $this->end;
        $this->template->type_id = $this->type_id ?? Mass::$type;
        $this->template->active = true;
        $this->template->overload = $this->template->overload / 100;
        $this->template->save();

        session()->flash('success', __('Template Saved Successfully'));

        if($this->isCreate) {
            $this->template = new Template;
            $this->start = null;
            $this->end = null;
        }
    }

    public function rules()
    {
        return [
            'template.description' => 'required',
            'template.number_of_places' => 'required|numeric',
            'template.overload' => 'required|numeric|between:0,100',
            'start' => 'required',
            'end' => 'required',
            'template.day_of_week' => 'required|numeric|between:0,6',
        ];
    }
}
