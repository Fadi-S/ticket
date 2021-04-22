
<x-master>
    <x-slot name="title">{{ $title }} | Ticket</x-slot>

    <x-card>

        <x-table.table x-data="{ events: {} }"
                       x-init="Echo.channel('tickets')
                            .listen('TicketReserved', (e) =>
                                events[e.eventId]['reservedPlaces'] = e.reserved
                            );
                        ">
            <x-slot name="head">
                <tr>
                    <x-table.th>{{ __('Description') }}</x-table.th>
                    <x-table.th>{{ __('Date') }}</x-table.th>
                    <x-table.th>{{ __('Time') }}</x-table.th>
                    <x-table.th>{{ __('Publish Date') }}</x-table.th>
                    <x-table.th>{{ __('Number of Places') }}</x-table.th>
                    <x-table.th>{{ __('Number of Deacon Places') }}</x-table.th>
                    @can('events.edit')
                        <x-table.empty-th>{{ __('Edit') }}</x-table.empty-th>
                    @endcan
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($events as $event)
                    @php($reservedCount = ($event->number_of_places-$event->reservations_left))
                    <tr @load.window="events[{{$event->id}}] = { reservedPlaces: {{ $reservedCount  }}, numberOfPlaces: {{ $event->number_of_places }} };"
                        class="{{ now()->between($event->start, $event->end) ? 'bg-green-200 dark:bg-green-500' : '' }}">
                        <x-table.td>
                            {{ $event->description }}
                        </x-table.td>

                        <x-table.td>
                            <a class="text-blue-500 hover:text-blue-600 underline font-semibold"
                               href="{{ url("/tickets/?event=$event->id") }}">{{ $event->formatted_date }}</a>
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right">
                            {{ $event->start->format("h:i A") }} - {{ $event->end->format("h:i A") }}
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right">
                            {{ $event->published_at->format("d/m/Y h:i A") }}
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right"
                                x-text="(events[{{$event->id}}]) ? events[{{$event->id}}].reservedPlaces + ' / ' + events[{{$event->id}}].numberOfPlaces : ''">
                            {{ ($reservedCount) . '/' . $event->number_of_places }}
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right">
                            {{ ($event->deaconNumber - $event->deaconReservationsLeft) . '/' . $event->deaconNumber }}
                        </x-table.td>

                        @can('events.edit')
                            <x-table.td>
                                <a class="bg-blue-400 px-4 py-2 hover:bg-blue-500
                                dark:bg-blue-600 dark:hover:bg-blue-700
                                 text-white text-md rounded-lg"
                                   href="{{ url("/$url/$event->id/edit") }}">{{ __('Edit') }}</a>
                            </x-table.td>
                        @endcan
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $events->links() }}

    </x-card>

</x-master>
