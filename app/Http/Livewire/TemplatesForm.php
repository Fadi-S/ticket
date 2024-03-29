<?php

namespace App\Http\Livewire;

use App\Models\Event;

use App\Models\EventType;
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

    public $enabled = true;

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

            $this->enabled = $this->template->active;
        }

    }

    public function getTypeProperty()
    {
        return EventType::find($this->type_id);
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
        $this->template->type_id = $this->type_id ?? 1;
        $this->template->church_id = auth()->user()->church_id;
        $this->template->active ??= true;
        $this->template->overload = $this->template->overload / 100;
        $this->template->save();

        Template::clearCache($this->type_id ?? 1);

        session()->flash('success', __('Template Saved Successfully'));

        if($this->isCreate) {
            $this->template = new Template;
            $this->start = null;
            $this->end = null;
        }
    }

    public function updatedEnabled()
    {
        $this->template->active = $this->enabled;

        if(!$this->isCreate) {
            Template::find($this->template->id)->update(['active' => $this->enabled]);

            Template::clearCache($this->type_id ?? 1);

            $state = ($this->enabled) ? __('Enabled') : __('Disabled');

            $this->dispatchBrowserEvent('message', [
                'level' => 'success',
                'message' => $state . ' ' . __('template successfully'),
                'important' => false,
            ]);
        }
    }

    public function rules()
    {
        return [
            'template.description' => 'required',
            'template.number_of_places' => 'required|numeric',
            'template.deacons_number' => 'nullable|numeric',
            'template.overload' => 'required|numeric|min:0',
            'start' => 'required',
            'end' => 'required',
            'template.day_of_week' => 'required|numeric|min:0|max:6',
        ];
    }
}
