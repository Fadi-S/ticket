<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventsRequest;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Template;
use Carbon\Carbon;

class EventsController extends Controller
{
    private $url;

    public function __construct()
    {
        $this->url = request()->segment(1);

        \View::share('type', EventType::whereUrl($this->url)->first());
    }

    public function create()
    {
        $this->authorize('create', Event::class);

        $start = Carbon::now();
        return view("events.create", [
            'start' => $start,
            'url' => $this->url
        ]);
    }

    public function store(EventType $eventType, EventsRequest $request)
    {
        $this->authorize('create', Event::class);

        $data = collect($request->all());
        $data->put('type_id', $eventType->id);
        $data->put('church_id', auth()->user()->church_id);
        if(Event::create($data->toArray()))
            flash()->success("Created event successfully");
        else
            flash()->error("Error creating event");

        Event::clearCache();

        return redirect("/$this->url/create");
    }

    public function edit($eventType, Event $event)
    {
        $this->authorize('update', $event);

        $start = Carbon::now();
        return view("events.edit", [
            'start' => $start,
            'url' => $this->url,
            'event' => $event,
        ]);
    }

    public function update($eventType, Event $event, EventsRequest $request)
    {
        $this->authorize('update', $event);

        if($event->update($request->all()))
            flash()->success("Edited event successfully");
        else
            flash()->error("Error editing event");

        Event::clearCache();

        return redirect("/$this->url/$event->id/edit");
    }

    public function index(EventType $eventType)
    {
        $this->authorize('index', Event::class);

        $events = Event::getByType($eventType->id);
        $templates = Template::getByType($eventType->id);

        return view("events.index", [
            'events' => $events,
            'templates' => $templates,
            'title' => 'View All Events',
            'type_id' => $eventType->id,
            'url' => $this->url,
        ]);
    }

    public function destroy($eventType, Event $event)
    {
        $this->authorize('delete', Event::class);

        $event->delete();

        flash()->success(__('Event deleted successfully'));

        return redirect()->to($this->url);
    }
}
