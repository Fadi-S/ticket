<x-card>

    <x-slot name="title">
        View your tickets
    </x-slot>

    @if($event && auth()->user()->can('tickets.view'))
        <script src="{{ mix('js/print.js') }}"></script>

        <div x-data class="mb-4" @print.window="printTickets($refs.print)">
            <x-button type="button" wire:click="export">
                <x-slot name="svg">
                    <x-svg.printer wire:loading.remove wire:target="export"/>
                    <x-svg.spinner wire:loading wire:target="export"/>
                </x-slot>

                {{ __('Print/Export as PDF') }}
            </x-button>


            @if($pdfRendered)
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
                            <td>{{ $this->eventModel->description }}</td>
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
                                          margin: 5px 0; padding: 2px 0;">{{ $user['arabic_name'] }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            @endforeach
                        </tr>

                    </table>
                </div>
            @endif

        </div>
    @endif

    @if(auth()->user()->can('tickets.view'))
        <div class="max-w-xl mb-6">
            {{--            <img class="w-10 mx-2" src="{{ asset('/images/algolia/algolia-blue-mark.svg') }}" alt="Search With Algolia">--}}

            <x-form.input autocomplete="off"
                          wire:model="search"
                          name="search"
                          id="search"
                          label="{{ __('Search') }}"/>
        </div>
    @endif

    @php
        $colors = [
            [
                'border' => 'border-indigo-500 dark:border-indigo-800',
                'background' => 'bg-indigo-200 dark:bg-indigo-800',
                'text' => 'text-indigo-500 dark:text-indigo-300',
            ],
            [
                'border' => 'border-green-500 dark:border-green-800',
                'background' => 'bg-green-200 dark:bg-green-300',
                'text' => 'text-green-500 dark:text-green-800',
            ],
            [
                'border' => 'border-gray-500 dark:border-gray-300',
                'background' => 'bg-gray-200 dark:bg-gray-300',
                'text' => 'text-gray-500 dark:text-gray-800',
            ],
            [
                'border' => 'border-yellow-500 dark:border-yellow-800',
                'background' => 'bg-yellow-200 dark:bg-yellow-800',
                'text' => 'text-yellow-700 dark:text-yellow-300',
            ],
            [
                'border' => 'border-gray-500 dark:border-gray-300',
                'background' => 'bg-gray-200 dark:bg-gray-300',
                'text' => 'text-gray-500 dark:text-gray-800',
            ],
        ];
    @endphp

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            @if($event)
                <div class="mb-2 flex text-gray-800 dark:text-gray-200 items-start justify-between">
                    <div class="flex flex-col items-start space-y-2">
                        <div class="font-semibold" dir="rtl">{{ $this->eventModel->description }}</div>
                        <div class="font-semibold" dir="ltr">{{ $this->eventModel->start->format('l, jS \o\f F Y') }}</div>
                        <div class="font-semibold" dir="ltr">{{ $this->eventModel->formatted_time }}</div>
                    </div>
                    @php($color = $colors[$this->eventModel->type_id-1])
                    <div class="font-bold text-sm rounded-full py-1 px-2
                                {{ $color['background'] . ' ' . $color['text'] }}">
                        {{ $this->eventModel->type->arabic_name }} {{ $this->eventModel->type_id == 1 ? $this->eventModel->eventOrderInDay() : '' }}
                    </div>
                </div>
            @endif
        </div>


        <x-layouts.tickets :tickets="$tickets" :colors="$colors" :event="$event" :type="$type">
            <x-slot name="empty">
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
            </x-slot>
        </x-layouts.tickets>

        <div class="col-span-12">
            @if(auth()->user()->isDeacon() || auth()->user()->isAdmin())
                <div class="font-bold my-4 text-3xl">
                    {{ __('Deacon') }}
                </div>
            <div class="grid grid-cols-12 gap-4">
                <x-layouts.tickets :tickets="$deacons"
                                   :colors="$colors"
                                   :event="$event"
                                   :type="$type"
                >
                    <x-slot name="empty">
                        @if($event)
                            <span>{{ __('No deacons in this event!') }}</span>
                        @else
                            <span>{{ __('You have no:type tickets!', ['type' => $type ? ' '.strtolower($this->typeModel->name) : '']) }}
                        <a href="{{ url('/reserve') }}"
                           class="text-blue-500 underline" data-turbolinks="false">{{ __('Make Reservation') }}</a>
                    </span>
                        @endif
                    </x-slot>
                </x-layouts.tickets>
            </div>

            @endif
        </div>
        </div>

    {{ $tickets->links() }}

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
