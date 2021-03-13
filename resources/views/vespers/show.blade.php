<x-master>

    <x-slot name="title">View Vesper {{ $vesper->formatted_date }} | Ticket</x-slot>

    <x-card class="space-y-4">
        <div class="space-y-2">
            <h6 class="text-2xl font-semibold text-gray-800">
                {{ $vesper->type->arabic_name }} | {{ $vesper->formatted_date }} | {{ $vesper->formatted_time }}
            </h6>

            <p class="text-sm text-gray-600">
                Places available: {{ $vesper->number_of_places - $vesper->reservedPlaces() . ' / ' . $vesper->number_of_places}}
            </p>
        </div>

        <x-table.table>
            <x-slot name="head">
                <x-table.th>User</x-table.th>
                <x-table.th>Reserved At</x-table.th>
                <x-table.empty-th>Edit</x-table.empty-th>
                <x-table.empty-th>Delete</x-table.empty-th>
            </x-slot>

            <x-slot name="body">
                @foreach($vesper->reservations as $reservation)
                <tr>
                    <x-table.td>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $reservation->user->picture }}" alt="{{ $reservation->user->name }}'s picture">
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
                        {{ $reservation->reserved_at->format('l, d/m/Y h:i a') }}
                    </x-table.td>

                    <x-table.td>
                        <a class="bg-blue-400 px-4 py-2 hover:bg-blue-500
                             text-white text-md rounded-lg"
                           href="{{ url("/reservations/$reservation->id/edit") }}">Edit</a>
                    </x-table.td>

                    <x-table.td>
                        {!! Form::open(["method" => "DELETE", "url" => url("/reservations/$reservation->id")]) !!}

                        <a href="#" class="bg-red-500 px-4 py-2 hover:bg-red-600 text-white text-md rounded-lg"
                           onclick="event.preventDefault();event.stopPropagation();
                           if(confirm('Are you sure you want to cancel this reservation?')) this.parentNode.submit();">
                            Cancel Reservation
                        </a>

                        {!! Form::close() !!}
                    </x-table.td>
                </tr>
            @endforeach
            </x-slot>

        </x-table.table>

    </x-card>
</x-master>
