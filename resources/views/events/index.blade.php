
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
                    <x-table.th>{{ __('Number of Places') }}</x-table.th>
                    <x-table.th>{{ __('Publish Date') }}</x-table.th>
                @can('events.edit')
                        <x-table.th>{{ __('Edit') }}</x-table.th>
                    @endcan
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($events as $event)
                    @php($reservedCount = ($event->number_of_places-$event->reservations_left))
                    <tr @load.window="events[{{$event->id}}] = { reservedPlaces: {{ $reservedCount  }}, numberOfPlaces: {{ $event->number_of_places }} };"
                        class="{{ now()->between($event->start, $event->end) ? 'bg-green-200 dark:bg-green-500' : '' }}">
                        <x-table.td>
                            <a class="text-blue-500 hover:text-blue-600 underline font-semibold"
                               href="{{ url("/tickets/?event=$event->id") }}">
                            {{ $event->description }}
                            </a>
                        </x-table.td>

                        <x-table.td>
                            <div dir="ltr" class="rtl:text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $event->formatted_date }}
                            </div>
                            <div dir="ltr" class="rtl:text-right dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                {{ $event->formatted_time }}
                            </div>
                        </x-table.td>

                        <x-table.td dir="ltr" className="rtl:text-right">

                            <div dir="ltr" x-text="(events[{{$event->id}}]) ? events[{{$event->id}}].reservedPlaces + ' / ' + events[{{$event->id}}].numberOfPlaces : ''"
                                 class="rtl:text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ ($reservedCount) . '/' . $event->number_of_places }}
                            </div>
                            <div dir="ltr" class="rtl:text-right dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                {{ ($event->deaconNumber - $event->deaconReservationsLeft) . ' / ' . $event->deaconNumber }}
                            </div>
                        </x-table.td>

                        <x-table.td dir="ltr" className="rtl:text-right">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full border
                                {{ $event->published_at->lte(now()) ? 'bg-green-100 text-green-800 border-green-800' : 'bg-red-100 text-red-800 border-red-800' }}">
                                {{ $event->published_at->format("d/m h:i A") }}
                            </span>
                        </x-table.td>

                        @can('events.edit')
                            <x-table.td>
                                <x-buttons.edit :url="url($url . '/' . $event->id . '/edit')" />
                            </x-table.td>
                        @endcan
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $events->links() }}

    </x-card>

</x-master>
