<x-slot name="title">
    View All Tickets | Ticket
</x-slot>

<x-card>

    @if(auth()->user()->can('tickets.view') && !$event)
        <x-button wire:click="$toggle('old')">
            <x-slot name="svg">
                <x-svg.eye wire:loading.remove wire:target="old"/>
                <x-svg.spinner wire:loading wire:target="old" />
            </x-slot>

            @if($old)
                {{ __('Hide Old Reservations') }}
            @else
                {{ __('Show Old Reservations') }}
            @endif
        </x-button>
    @endif

    <div class="flex items-center justify-between sm:justify-start space-x-2">
        @if($event && auth()->user()->can('tickets.view'))
            <div>
                @if(auth()->user()->can('events.show'))
                    <x-button wire:click="toggleEvent" class="ml-2"
                              color="bg-green-500 hover:bg-green-600
                          dark:bg-green-600 dark:hover:bg-green-700">
                        <x-slot name="svg">
                            @if($this->eventModel->hidden_at)
                                <x-svg.eye wire:loading.remove wire:target="toggleEvent" />
                            @else
                                <x-svg.edit wire:loading.remove wire:target="toggleEvent" />
                            @endif
                            <x-svg.spinner wire:loading wire:target="toggleEvent" />
                        </x-slot>

                        @if($this->eventModel->hidden_at)
                            {{ __('Show Event') }}
                        @else
                            {{ __('Hide Event') }}
                        @endif
                    </x-button>
                @endif
            </div>

        <div>
            <x-button type="button"
                      color="bg-indigo-400 hover:bg-indigo-500 dark:bg-indigo-500
                       dark:hover:bg-indigo-600 text-white"
                      wire:click="export">
                <x-slot name="svg">
                    <x-svg.printer wire:loading.remove wire:target="export"/>
                    <x-svg.spinner wire:loading wire:target="export"/>
                </x-slot>

                {{ __('Print/Export as PDF') }}
            </x-button>
        </div>
        @endif
    </div>


    @if(auth()->user()->can('tickets.view'))
        <div class="max-w-xl mb-6 mt-4">
            {{--            <img class="w-10 mx-2" src="{{ asset('/images/algolia/algolia-blue-mark.svg') }}" alt="Search With Algolia">--}}

            <x-form.input autocomplete="off"
                          wire:model="search"
                          name="search"
                          id="search"
                          label="{{ __('Search') }}"/>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            @if($event)
                <div class="mb-2 flex flex-col text-gray-800 space-y-4
                 dark:text-gray-200 items-start justify-between">
                    @php($color = $this->eventModel->type->colorNames)
                    <div class="font-bold text-sm rounded-full py-1 px-2"
                        style="color: {{ $color['text'] }}; background-color: {{ $color['background'] }};"
                    >
                        {{ $this->eventModel->type->arabic_name }}
                    </div>

                    <div class="flex flex-col items-start space-y-2">
                        <div class="font-semibold" dir="rtl">{{ $this->eventModel->description }}</div>
                        <div class="dark:text-gray-400 font-semibold text-gray-500 text-sm">{{ $this->eventModel->formatted_time }}</div>
                        <div class="dark:text-gray-400 font-semibold text-gray-500 text-xs">{{ $this->eventModel->start->translatedFormat('l, jS F Y') }}</div>
                    </div>
                </div>
            @endif
        </div>


        <x-layouts.tickets :tickets="$tickets" :event="$event" :type="$type">
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

        @if($deacons->isNotEmpty() && (auth()->user()->isDeacon() || auth()->user()->can('tickets.view')))
            <div class="col-span-12 mb-6">
                    <div class="font-bold my-4 text-3xl">
                        {{ __('Deacon') }}
                    </div>
                <div class="grid grid-cols-12 gap-4">
                    <x-layouts.tickets :tickets="$deacons"
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
            </div>
        @endif

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
                <h3 class="text-lg rtl:text-right mx-2 leading-6 font-medium text-gray-900 dark:text-gray-100
                flex items-center sm:justify-start sm:mr-3 justify-center" id="modal-headline">
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
                <div class="space-x-2 flex justify-between">
                    <x-button type="button" @click="open = false; Livewire.emit('cancelReservation', details.reservationId);" class="ml-2"
                              color="bg-red-600 hover:bg-red-700 text-white">
                        {{ __('Cancel Reservation') }}
                    </x-button>

                    <x-button class="mx-2" type="button" @click="open = false;"
                              color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       text-gray-900 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                        {{ __('Close') }}
                    </x-button>
                </div>


            </x-slot>
        </x-layouts.modal>
    @endpush

</x-card>
