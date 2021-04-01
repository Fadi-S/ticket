
<x-master>
    <x-slot name="title">View All Masses | Ticket</x-slot>

    <x-card>

        <x-table.table x-data="{ events: {} }"
                       x-init="Echo.channel('tickets')
                            .listen('TicketReserved', (e) =>
                                events[e.eventId]['reservedPlaces'] = e.reserved
                            );
                        ">
            <x-slot name="head">
                <tr>
                    <x-table.th>{{ __('Date') }}</x-table.th>
                    <x-table.th>{{ __('Time') }}</x-table.th>
                    <x-table.th>{{ __('Publish Date') }}</x-table.th>
                    <x-table.th>{{ __('Number of Places') }}</x-table.th>
                    @can('events.edit')
                        <x-table.empty-th>{{ __('Edit') }}</x-table.empty-th>
                    @endcan
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($masses as $mass)
                    <tr @load.window="events[{{$mass->id}}] = { reservedPlaces: {{ $mass->reservedPlaces() }}, numberOfPlaces: {{ $mass->number_of_places }} };"
                        class="{{ now()->between($mass->start, $mass->end) ? 'bg-green-200 dark:bg-green-500' : '' }}">
                        <x-table.td>
                            <a class="text-blue-500 hover:text-blue-600 underline font-semibold"
                               href="{{ url("/tickets/?event=$mass->id") }}">{{ $mass->formatted_date }}</a>
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right">
                            {{ $mass->start->format("h:i A") }} - {{ $mass->end->format("h:i A") }}
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right">
                            {{ $mass->published_at->format("d/m/Y h:i A") }}
                        </x-table.td>

                        <x-table.td x-text="(events[{{$mass->id}}]) ? events[{{$mass->id}}].reservedPlaces + ' / ' + events[{{$mass->id}}].numberOfPlaces : ''">
                            {{ $mass->reservedPlaces() . '/' . $mass->number_of_places }}
                        </x-table.td>

                        @can('events.edit')
                            <x-table.td>
                                <a class="bg-blue-400 px-4 py-2 hover:bg-blue-500
                                dark:bg-blue-600 dark:hover:bg-blue-700
                                 text-white text-md rounded-lg"
                                   href="{{ url("/masses/$mass->id/edit") }}">{{ __('Edit') }}</a>
                            </x-table.td>
                        @endcan
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $masses->links() }}

    </x-card>

</x-master>
