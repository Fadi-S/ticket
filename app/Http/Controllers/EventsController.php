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

    public function store(EventType $type, EventsRequest $request)
    {
        $this->authorize('create', Event::class);

        $data = collect($request->all());
        $data->put('type_id', $type->id);
        if($event = Event::create($data->toArray()))
            flash()->success("Created event successfully");
        else
            flash()->error("Error creating event");

        $recurring = !! $request->get('recurring', false);
        if($recurring) {
            Template::fromEvent($event);
        }

        return redirect("/$this->url/create");
    }

    public function edit(EventType $type, Event $event)
    {
        $this->authorize('update', $event);

        $start = Carbon::now();
        return view("events.edit", [
            'start' => $start,
            'url' => $this->url,
            'event' => $event,
        ]);
    }

    public function update(EventType $type, Event $event, EventsRequest $request)
    {
        $this->authorize('update', $event);

        if($event->update($request->all()))
            flash()->success("Edited event successfully");
        else
            flash()->error("Error editing event");

        $recurring = !! $request->get('recurring', false);
        if($recurring) {
            Template::fromEvent($event);
        }

        return redirect("/$this->url/$event->id/edit");
    }

    public function index(EventType $type)
    {
        $this->authorize('index', Event::class);

        $events = Event::typeId($type->id)
            ->orderBy('start')
            ->upcoming()
            ->with('tickets.reservations.user')
            ->paginate(10);

        $templates = Template::type($type->id)
            ->orderByDesc('active')
            ->get();

        return view("events.index", [
            'events' => $events,
            'templates' => $templates,
            'title' => 'View All Events',
            'type_id' => $type->id,
            'url' => $this->url,
        ]);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', Event::class);

        $event->delete();

        flash()->success(__('Event deleted successfully'));

        return redirect()->to($this->url);
    }
}
