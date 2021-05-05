<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventsRequest;
use App\Models\Baskha;
use App\Models\BaskhaOccasion;
use App\Models\Event;
use App\Models\Kiahk;
use App\Models\Mass;
use App\Models\Template;
use App\Models\User\User;
use App\Models\Vesper;
use Carbon\Carbon;

class EventsController extends Controller
{
    private $url;
    private $model;

    public function __construct()
    {
        $this->url = request()->segment(1);

        $this->model = [
            'masses' => Mass::class,
            'vespers' => Vesper::class,
            'kiahk' => Kiahk::class,
            'baskha' => Baskha::class,
            'holy' => BaskhaOccasion::class,
        ][$this->url] ?? Event::class;

        $reflection = new \ReflectionClass($this->model);
        $this->model = $reflection->newInstance();

//        $this->authorizeResource(Event::class, 'event');
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

    public function store(EventsRequest $request)
    {
        $this->authorize('create', Event::class);

        if($event = $this->model::create($request->all()))
            flash()->success("Created event successfully");
        else
            flash()->error("Error creating event");

        $recurring = !! $request->get('recurring', false);
        if($recurring) {
            Template::fromEvent($event);
        }

        return redirect("/$this->url/create");
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $start = Carbon::now();
        return view("events.edit", [
            'start' => $start,
            'url' => $this->url,
            'event' => $event,
        ]);
    }

    public function update(Event $event, EventsRequest $request)
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

    public function index()
    {
        $this->authorize('index', Event::class);

        $events = $this->model->orderBy('start')
            ->upcoming()
            ->with('tickets.reservations.user')
            ->paginate(10);

        $templates = Template::type($this->model->type_id)->get();

        return view("events.index", [
            'events' => $events,
            'templates' => $templates,
            'title' => 'View All Events',
            'type_id' => $this->model->type_id,
            'url' => $this->url,
        ]);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', Event::class);

        $event->delete();

        flash()->success(__('Event deleted successfully'));

        redirect($this->url);
    }
}
