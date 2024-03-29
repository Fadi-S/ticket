@props([
    'tickets' => [],
    'colors' => [],
    'event' => null,
])

@forelse($tickets as $ticket)
    @php($color = $ticket->event->type->colorNames)
    <div id="ticket-{{ $ticket->id }}" wire:key="ticket-{{ $ticket->id }}" class="transition-colors duration-500
            col-span-12 md:col-span-6 xl:col-span-4 overflow-x-hidden w-full
             border-t-4 rounded-lg" style="border-color: {{ $color['border'] }}">
        @if(!$event)
            <h3 class="flex text-gray-800 dark:text-gray-200
                    bg-gray-50 dark:bg-gray-800 transition-dark
                     items-start justify-between p-4">
                <div class="flex flex-col items-start">
                    <div class="font-semibold" dir="rtl">{{ $ticket->event->description }}</div>
                    <div class="dark:text-gray-400 font-semibold text-gray-500 text-sm" >{{ $ticket->event->formatted_time }}</div>
                    <div class="dark:text-gray-400 font-semibold text-gray-500 text-xs">{{ $ticket->event->start->translatedFormat('l, jS F Y') }}</div>
                </div>

                <span class="font-bold text-sm rounded-full py-1 px-2"
                      style="color: {{ $color['text'] }}; background-color: {{ $color['background'] }};"
                >
                            {{ $ticket->event->type->arabic_name }}
                        </span>
            </h3>
        @endif
        <ul class="space-y-6 bg-gray-100 dark:bg-gray-900 p-4 transition-dark">
            @foreach($ticket->reservations as $reservation)
                @unless($reservation->user)
                    @continue
                @endunless
                <li wire:key="reservation-{{ $reservation->id }}" id="reservation-{{ $reservation->id }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $reservation->user->picture }}"
                                     alt="{{ $reservation->user->locale_name }}'s picture">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    @if(config('settings.arabic_name_only'))
                                        {{ $reservation->user->arabic_name }}
                                    @else
                                        {{ $reservation->user->name }}
                                    @endif
                                </div>
                                @unless(config('settings.arabic_name_only'))
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $reservation->user->arabic_name }}
                                    </div>
                                @endunless
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    #{{ $reservation->user->id }}
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->can('reservations.bypass') || (!$ticket->event->hasPassed() && $reservation->of(auth()->user())))
                            <x-buttons.cancel x-data="{}" @click="$dispatch('open', { reservationId: {{ $reservation->id }} })" />
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
            @unless(auth()->user()->isUser())
                <div class="flex bg-gray-100 dark:bg-gray-800 shadow
                 rounded-b-lg transition-dark px-2 py-1 text-sm text-gray-400
                         items-start justify-between">
                    <span>
                        {{ __('By : :name', ['name' => ($ticket->reservedBy) ? $ticket->reservedBy->locale_name : '[DELETED]' ]) }}
                    </span>
                    <div dir="ltr">{{ $ticket->reserved_at->format('d/m h:i a')  }}</div>
                </div>
            @endunless

    </div>
@empty
    <p class="font-bold text-gray-600 dark:text-gray-300 text-md mx-auto col-span-12 flex justify-center">
        <x-svg.ticket class="text-gray-500 dark:text-gray-300 mx-2"/>
        {{ $empty }}
    </p>
@endforelse
