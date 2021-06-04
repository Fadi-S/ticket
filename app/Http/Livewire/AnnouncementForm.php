<?php

namespace App\Http\Livewire;

use App\Models\Announcement;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AnnouncementForm extends Component
{
    use AuthorizesRequests;

    public Announcement $announcement;
    public bool $isCreate;
    public $start;
    public $end;

    public function mount()
    {
        $this->authorize('create', Event::class);
        $this->isCreate = ! isset($this->announcement);

        $this->announcement ??= new Announcement;

        if(!$this->isCreate) {
            $this->start = $this->announcement->start->format('Y-m-d h:i A');
            $this->end = $this->announcement->end->format('Y-m-d h:i A');
        }
    }

    public function render()
    {
        return view('livewire.announcement-form');
    }

    public function save()
    {
        $this->validate();

        $this->announcement->start = $this->start;
        $this->announcement->end = $this->end;

        $this->announcement->save();

        \Cache::forget('announcements');

        session()->flash('success', __(':type Saved Successfully', ['type' => __('Announcement')]));

        if($this->isCreate) {
            $this->announcement = new Announcement;
            $this->start = null;
            $this->end = null;
        }
    }

    public function rules()
    {
        return [
            'announcement.title' => ['required'],
            'announcement.body' => ['required'],
            'announcement.color' => ['required'],
            'announcement.url' => ['nullable', 'url'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after:start'],
        ];
    }
}
