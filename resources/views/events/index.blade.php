
<x-master>
    <x-slot name="title">{{ $title }} | Ticket</x-slot>

    <x-card>

        <a class="bg-blue-500 px-4 py-2 rounded-lg my-2 hover:bg-blue-600 dark:hover:bg-blue-900
         dark:bg-blue-800 transition-dark text-white"
           href="{{ url('templates/create?type_id=' . $type_id) }}">
            {{ __('Add Event Template') }}
        </a>

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
               @can('events.notices')
                    <x-table.th>{{ __('Notices') }}</x-table.th>
               @endcan
                @can('events.edit')
                        <x-table.th>{{ __('Edit') }}</x-table.th>
                    @endcan
                </tr>
            </x-slot>

            <x-slot name="body">

                @forelse($events as $event)
                    @php($reservedCount = ($event->number_of_places-$event->reservations_left))
                    <tr @load.window="events[{{$event->id}}] = { reservedPlaces: {{ $reservedCount  }}, numberOfPlaces: {{ $event->number_of_places }} };"
                        class="{{ now()->between($event->start, $event->end) ? 'bg-gray-200 dark:bg-gray-500' : '' }}">
                        <x-table.td>
                            <a class="text-blue-500 hover:text-blue-600 underline font-semibold"
                               href="{{ url("/tickets/?event=$event->id") }}">
                            {{ $event->description }}
                            </a>
                        </x-table.td>

                        <x-table.td>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $event->formatted_date }}
                            </div>
                            <div class="dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                {{ $event->formatted_time }}
                            </div>
                        </x-table.td>

                        <x-table.td dir="ltr" className="rtl:text-right">

                            <div dir="ltr" x-text="(events[{{$event->id}}]) ? events[{{$event->id}}].reservedPlaces + ' / ' + events[{{$event->id}}].numberOfPlaces : ''"
                                 class="rtl:text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ ($reservedCount) . '/' . $event->number_of_places }}
                            </div>
                            @if($event->hasDeacons)
                                <div dir="ltr" class="rtl:text-right dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                    {{ ($event->deaconNumber - $event->deaconReservationsLeft) . ' / ' . $event->deaconNumber }}
                                </div>
                            @endif

                        </x-table.td>

                        <x-table.td>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full border
                                {{ $event->published_at->lte(now()) ? 'bg-green-100 text-green-800 border-green-800' : 'bg-red-100 text-red-800 border-red-800' }}">
                                {{ $event->published_at->translatedFormat("d/m h:i A") }}
                            </span>
                        </x-table.td>

                        @can('events.notices')
                            <x-table.td>
                                <button x-data="{ notice: `{{ $event->notice }}` }"
                                        @notice-edited-{{$event->id}}.window="notice=$event.detail.notice"
                                        @click="$dispatch('open-notice', '{{ $event->id }}')"
                                        type="button" class="text-xs text-blue-500 dark:text-blue-200 focus:outline-none">

                                    <div x-show="!! notice"
                                         class="truncate w-28" x-text="notice"
                                         style="display: none">
                                        {{ $event->notice }}
                                    </div>

                                    @if(!$event->notice)
                                        <div x-show="! notice">
                                            {{ __('Add Notice') }}
                                        </div>
                                    @endif
                                </button>

                            </x-table.td>
                        @endcan

                        @can('events.edit')
                            <x-table.td>
                                <x-buttons.edit :url="url($url . '/' . $event->id . '/edit')" />
                            </x-table.td>
                        @endcan
                    </tr>

                @empty

                    <tr>
                        <x-table.td colspan="6">
                            <div class="flex items-center justify-center">
                                <x-svg.ticket />

                                <div class="mx-2">
                                    {{ __('No Upcoming Events') }}
                                </div>
                            </div>
                        </x-table.td>
                    </tr>

                @endforelse

            </x-slot>

        </x-table.table>

        {{ $events->links() }}

    </x-card>

    <div x-data="{ open: false, }"
         @open-notice.window="open=true; window.livewire.emit('set:event-notice', $event.detail)"
         x-init="
  () => document.body.classList.add('overflow-hidden');
  $watch('open', value => {
    if (value === true) { document.body.classList.add('overflow-hidden') }
    else { document.body.classList.remove('overflow-hidden') }
  });" x-show="open" style="display: none;" class="fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen text-center sm:block sm:p-0">

            <div x-show="open" x-description="Background overlay, show/hide based on modal state."
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div @click.away=open=false x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"

                 class="inline-block bg-white dark:bg-gray-700 text-left overflow-hidden shadow-xl sm:max-w-xl rounded-lg w-full
                      transform transition-all sm:my-8 sm:align-middle"
                 role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                    <div class="sm:flex">

                        <div class="mt-3 text-center sm:mt-0 py-4 px-6 w-full">
                            <livewire:view-event-notice />
                        </div>

                    </div>

            </div>
        </div>
    </div>

    @if($templates->isNotEmpty())
    <x-card>

        <x-button x-data="{}" x-on:click="$dispatch('open')" class="mb-6">
            <x-slot name="svg">
                <x-svg.add />
            </x-slot>

            {{ __('Generate Events Automatically') }}
        </x-button>

        <div class="flex flex-col space-y-4 items-start">
            <h1 class="text-lg font-semibold">
                {{ __('Templates') }}
            </h1>
        </div>


        <x-table.table>
            <x-slot name="head">
                <tr>
                    <x-table.th>{{ __('Description') }}</x-table.th>
                    <x-table.th>{{ __('Day Of Week') }}</x-table.th>
                    <x-table.th>{{ __('Number of Places') }}</x-table.th>
                    <x-table.th>{{ __('Deacon Places') }}</x-table.th>
                    <x-table.th>{{ __('Active') }}</x-table.th>
                @can('events.edit')
                        <x-table.th>{{ __('Edit') }}</x-table.th>
                    @endcan
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($templates as $template)
                    <tr>
                        <x-table.td>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $template->description }}
                            </div>
                        </x-table.td>

                        <x-table.td>
                            <div dir="ltr" class="rtl:text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ now()->next($template->day_of_week)->translatedFormat('l') }}
                            </div>
                            <div class="dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                {{ $template->start->translatedFormat('h:i a') }} -> {{ $template->end->translatedFormat('h:i a') }}
                            </div>
                        </x-table.td>

                        <x-table.td dir="ltr" className="rtl:text-right">

                            <div dir="ltr" class="rtl:text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $template->number_of_places }}
                            </div>

                            <div class="dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                +{{ $template->overload * 100 }}%
                            </div>
                        </x-table.td>

                        <x-table.td dir="ltr" className="rtl:text-right">
                            <div dir="ltr" class="rtl:text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $template->deacons_number }}
                            </div>
                        </x-table.td>

                        <x-table.td>
                            <x-svg.active-or-not :active="$template->active" />
                        </x-table.td>

                        @can('events.edit')
                            <x-table.td>
                                <x-buttons.edit :url="url('templates/' . $template->id . '/edit?type_id=' . $type_id)" />
                            </x-table.td>
                        @endcan
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        @push('modals')
            <x-layouts.modal id="user-form-modal" :force="true" size="w-full rounded-none sm:rounded-lg md:max-w-2xl
                 lg:max-w-4xl my-2 sm:max-w-xl" @open.window="open=true" @close.window="open=false">
                <x-slot name="dialog">
                    <div class="px-6 py-10">
                        <livewire:generate-recurring-events :type="$type_id" />
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <div class="space-x-2 flex flex-row-reverse">

                        <x-button class="mx-2" type="button" @click="open = false;"
                                  color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       text-gray-900 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                            {{ __("Cancel") }}
                        </x-button>
                    </div>
                </x-slot>
            </x-layouts.modal>
        @endpush
    </x-card>
    @endif

</x-master>
