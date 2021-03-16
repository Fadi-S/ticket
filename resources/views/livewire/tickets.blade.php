<x-card>

    <x-slot name="title">
        View your tickets
    </x-slot>

    @if(auth()->user()->can('tickets.view') && $event)
        <script src="{{ mix('js/print.js') }}"></script>

        <div x-data="{  }" class="mb-4">
            <x-button type="button" @click="printTickets($refs.print)">
                <x-slot name="svg">
                    <x-svg.printer />
                </x-slot>

                {{ __('Print/Export as PDF') }}
            </x-button>

            <div x-ref="print" class="hidden">
                <table dir="rtl" width="100%">
                    <tr style="color: #4f73ff; font-weight: bold; text-align: center">
                        <th>التاريخ</th>
                        <th>الميعاد</th>
                        <th>أسم الكنيسة</th>
                        <th>مناسبة الحضور</th>
                    </tr>
                    <tr style="text-align: center;">
                        <td dir="ltr">{{ $this->eventModel->start->format('l, F j, Y') }}</td>
                        <td dir="ltr">{{ $this->eventModel->start->format('h:i a') }}</td>
                        <td>كنيسة الشهيد العظيم مارجرجس باسبورتنج</td>
                        <td>{{ $this->eventModel->type->arabic_name }} {{ $this->eventModel->eventOrderInDay() }}</td>
                    </tr>
                </table>

                <br><br>

                <table dir="rtl" width="100%">

                    <tr style="text-align: center; font-size: 25px; font-weight: bold;">
                        <th>سيدات</th>
                        <th>رجال</th>
                    </tr>

                    <tr style="text-align: center;">
                        @foreach($users as $gender => $list)
                            <td>
                                <ul style="list-style-type: none; height: 80vh;">
                                    @foreach($list as $user)
                                        <li style="vertical-align: top;
                                         border: #ef8c82 solid thin;
                                          margin: 5px 0; padding: 2px 0;">{{ $user->smart_name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        @endforeach
                    </tr>

                </table>
            </div>
        </div>
    @endif

    <div class="mb-4 w-full xl:w-1/3 lg:w-1/2 mx-auto">
        <x-form.input autocomplete="off" wire:model="search" name="search" id="search" label="{{ __('Search') }}"/>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            @if($event)
                <div class="mb-2 flex flex-col text-gray-800 dark:text-gray-200">
                    <div class="font-semibold" dir="ltr">{{ $this->eventModel->start->format('l, jS \o\f F Y') }}</div>

                    <div class="font-semibold" dir="ltr">{{ $this->eventModel->formatted_time }}</div>

                    <div class="font-bold text-sm text-gray-700 dark:text-gray-300">{{ $this->eventModel->type->arabic_name }}</div>
                </div>
            @endif
        </div>

        @forelse($tickets as $ticket)
            <div id="ticket-{{ $ticket->id }}" class="bg-gray-100 dark:bg-gray-800 shadow-inner transition-colors duration-500
            rounded-lg p-4 col-span-12 md:col-span-6 xl:col-span-4 overflow-x-hidden">
                @if(!$event)
                    <h3 class="mb-2 w-full flex flex-col text-gray-800 dark:text-gray-200">
                        <div class="font-semibold" dir="ltr">{{ $ticket->event->start->format('l, jS \o\f F Y') }}</div>
                        <div class="font-semibold" dir="ltr">{{ $ticket->event->formatted_time }}</div>

                        <span class="font-bold text-sm text-gray-700 dark:text-gray-300">{{ $ticket->event->type->arabic_name }}</span>
                    </h3>
                @endif
                <x-table.table class="w-full">
                    <x-slot name="head">
                        <x-table.th>{{ __('User') }}</x-table.th>
                        <x-table.empty-th>Cancel</x-table.empty-th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($ticket->reservations as $reservation)
                            <tr id="reservation-{{ $reservation->id }}">
                                <x-table.td>
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $reservation->user->picture }}"
                                                 alt="{{ $reservation->user->name }}'s picture">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $reservation->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
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
            <p class="font-bold text-gray-600 dark:text-gray-300 text-md mx-auto col-span-12 flex justify-center">
                <x-svg.ticket class="text-gray-500 dark:text-gray-300 mx-2"/>
                @if($event)
                    <span>{{ __('You have no tickets at this event!') }}
                        <a href="{{ url('tickets') }}"
                           class="text-blue-500 underline">{{ __('View All tickets') }}</a>
                    </span>
                @else
                    <span>{{ __('You have no:type tickets!', ['type' => $type ? ' '.strtolower($this->typeModel->name) : '']) }}
                        <a href="{{ url('/reserve') }}"
                           class="text-blue-500 underline" data-turbolinks="false">{{ __('Make Reservation') }}</a>
                    </span>
                @endif
            </p>
        @endforelse

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
                <h3 class="text-lg rtl:text-right mx-2 leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                    {{ __('Cancel Reservation') }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-300">
                        {{ __('Are you sure you want to Cancel your reservation?') }}
                        {{ __('You might not be able to reserve again.') }}
                    </p>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="space-x-2 flex flex-row-reverse">
                    <x-button type="button" @click="open = false; Livewire.emit('cancelReservation', details.reservationId);" class="ml-2"
                              color="bg-red-600 hover:bg-red-700 text-white">
                        {{ __('Cancel Reservation') }}
                    </x-button>

                    <x-button class="mx-2" type="button" @click="open = false;"
                              color="bg-white text-gray-700 hover:bg-gray-50 border border-gray-400">
                        {{ __('Close') }}
                    </x-button>
                </div>


            </x-slot>
        </x-layouts.modal>
    @endpush

</x-card>
