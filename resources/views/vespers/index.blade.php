<x-master>
    <x-slot name="title">View All Vesper Events | Ticket</x-slot>

    <x-card>

        <x-table.table>
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

                @foreach($vespers as $vesper)
                    <tr class="{{ now()->between($vesper->start, $vesper->end) ? 'bg-green-200' : '' }}">
                        <x-table.td>
                            <a class="text-blue-500 hover:text-blue-600 underline font-semibold"
                               href="{{ url("/tickets/?event=$vesper->id") }}">{{ $vesper->start->format("l, dS F Y") }}</a>
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right">
                            {{ $vesper->start->format("h:i A") }} - {{ $vesper->end->format("h:i A") }}
                        </x-table.td>

                        <x-table.td dir="ltr" class="rtl:text-right">
                            {{ $vesper->published_at->format("d/m/Y h:i A") }}
                        </x-table.td>

                        <x-table.td x-text="(events[{{$vesper->id}}]) ? events[{{$vesper->id}}].reservedPlaces + ' / ' + events[{{$vesper->id}}].numberOfPlaces : ''">
                            {{ $vesper->reservedPlaces() . '/' . $vesper->number_of_places }}
                        </x-table.td>

                        @can('events.edit')
                            <x-table.td>
                                <a class="bg-blue-400 hover:bg-blue-500
                                px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700
                                 text-white text-md rounded-lg"
                                   href="{{ url("/vespers/$vesper->id/edit") }}">{{ __('Edit') }}</a>
                            </x-table.td>
                        @endcan
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $vespers->links() }}

    </x-card>

</x-master>