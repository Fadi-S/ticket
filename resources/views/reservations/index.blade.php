<x-master>
    <x-slot name="title">View All Reservations | Ticket</x-slot>

    <x-card>

        <x-table.table>
            <x-slot name="head">
                <tr>
                    <x-table.empty-th>Type</x-table.empty-th>
                    <x-table.th>Time</x-table.th>
                    <x-table.th>User</x-table.th>
                    <x-table.th>Reserved At</x-table.th>
                    <x-table.empty-th>Cancel</x-table.empty-th>
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($reservations as $reservation)
                    <tr>
                        <x-table.td>{{ $reservation->event->type->arabic_name }}</x-table.td>

                        <x-table.td>{{ $reservation->event->formatted_date . ' | ' . $reservation->event->formatted_time }}</x-table.td>

                        <x-table.td>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $reservation->user->picture }}"
                                         alt="{{ $reservation->user->name }}'s picture">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $reservation->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $reservation->user->email }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>

                        <x-table.td>
                            {{ $reservation->ticket->reserved_at->format('l, d/m/Y h:i a') }}
                        </x-table.td>

                        <x-table.td>
                            @if(! $reservation->ticket->event->hasPassed())
                                {!! Form::open(["method" => "DELETE", "url" => url("/reservations/$reservation->id")]) !!}

                                <x-button href="#" color="bg-red-500 hover:bg-red-600"
                                   onclick="event.preventDefault();event.stopPropagation();
                               if(confirm('Are you sure you want to cancel this reservation?')) this.parentNode.submit();">
                                    <x-slot name="svg">
                                        <x-svg.x />
                                    </x-slot>
                                </x-button>

                                {!! Form::close() !!}
                            @endif

                        </x-table.td>
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $reservations->links() }}

    </x-card>

</x-master>
