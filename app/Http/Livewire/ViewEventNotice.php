<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;

class ViewEventNotice extends Component
{
    protected $listeners = [
        'set:event-notice' => 'setEvent'
    ];

    public Event $event;
    public $rules = [
        'event.notice' => 'nullable',
    ];

    public function render()
    {
        return view('livewire.view-event-notice');
    }

    public function setEvent($eventId)
    {
        $this->event = Event::find($eventId);
    }

    public function save()
    {
        $this->event->save();

        Event::clearCache();

        $this->dispatchBrowserEvent("notice-edited-{$this->event->id}", [
            'notice' => $this->event->notice
        ]);

        session()->flash('success', __(':type Saved Successfully', ['type' => __('Notice')]));
    }
}
