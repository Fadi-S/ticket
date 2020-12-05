<x-card class="">

    <div class="grid xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 gap-4">
        @if($eventModel)
            <div class="col-span-3">
                <div class="mb-2 flex flex-col text-gray-800">
                    <div class="font-semibold">{{ $eventModel->start->format('l, d/m/Y') }}</div>

                    <div class="font-semibold">{{ $eventModel->formatted_time }}</div>

                    <div class="font-bold text-sm text-gray-700">{{ $eventModel->type->arabic_name }}</div>
                </div>
            </div>
        @endif
        @forelse($tickets as $ticket)
            <div class="bg-gray-100 shadow-inner rounded-lg p-4 col-span-1 overflow-x-hidden">
                @if(!$event)
                    <h3 class="mb-2 w-full flex flex-col text-gray-800">
                        <div class="font-semibold">{{ $ticket->event->start->format('l, d/m/Y') }}</div>
                        <div class="font-semibold">{{ $ticket->event->formatted_time }}</div>

                        <span class="font-bold text-sm text-gray-700">{{ $ticket->event->type->arabic_name }}</span>
                    </h3>
                @endif
                <x-table.table class="w-full">
                    <x-slot name="head">
                        <x-table.th>User</x-table.th>
                        <x-table.empty-th>Cancel</x-table.empty-th>
                    </x-slot>

                    <x-slot name="body">
                        @foreach($ticket->reservations as $reservation)
                            <tr>
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
                                    @if(!$ticket->event->hasPassed() && $reservation->of(auth()->user()))
                                        <x-button color="bg-red-500 hover:bg-red-600" x-data="{  }"
                                                  @click="$dispatch('open', { reservationId: {{ $reservation->id }} })">
                                            <x-slot name="svg">
                                                <x-svg.x/>
                                            </x-slot>
                                        </x-button>
                                    @endif
                                </x-table.td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-table.table>
            </div>

        @empty
            <p class="font-bold text-gray-600 text-md mx-auto col-span-4 flex justify-center">
                <x-svg.ticket class="text-gray-500 mr-2"/>
                @if($event)
                    <span>You have no tickets at this event!
                        <a href="{{ url('tickets') }}"
                           class="text-blue-500 underline">View All tickets</a>
                    </span>
                @else
                    <span>You have no{{ $typeModel ? ' '.strtolower($typeModel->name) : '' }} tickets!
                        <a href="{{ url('reservations/create') }}"
                           class="text-blue-500 underline">Make a Reservation</a>
                    </span>
                @endif
            </p>
        @endforelse
    </div>

    <div class="mt-2">
        {{ $tickets->links() }}
    </div>

    @push('modals')
        <x-layouts.modal @open.window="open=true; details['reservationId'] = $event.detail.reservationId;">
            <x-slot name="svg">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </x-slot>

            <x-slot name="body">
                <h3  class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                    Cancel Reservation
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to Cancel your reservation?
                        You might not be able to reserve again.
                    </p>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="space-x-2 flex flex-row-reverse">
                    <x-button type="button" @click="open = false; Livewire.emit('cancelReservation', details.reservationId);" class="ml-2"
                              color="bg-red-600 hover:bg-red-700 text-white">
                        Cancel Reservation
                    </x-button>

                    <x-button class="mx-2" type="button" @click="open = false;"
                              color="bg-white text-gray-700 hover:bg-gray-50 border border-gray-400">
                        Close
                    </x-button>
                </div>


            </x-slot>
        </x-layouts.modal>
    @endpush

</x-card>
