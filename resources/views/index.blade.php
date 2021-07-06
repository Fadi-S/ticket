<x-master>
    <x-slot name="title">
        Home | Ticket
    </x-slot>

    <div class="my-4 mx-5 grid items-start grid-cols-12 gap-5">

        @if(!$periods && auth()->user()->can('events.create'))
            <x-data-card color="bg-red-800">
                <x-slot name="svg">
                    <x-svg.exclamation-circle color="text-red-100" />
                </x-slot>

                <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ __('No Period is set up, reservations won\'t work') }}</h4>
                <div class="text-gray-500">

                </div>
            </x-data-card>
        @endif

        <x-data-card :href="url('/reserve')" color="bg-red-600"
                     data-step="3"
                     data-intro="{{ __('You can click here to reserve') }}">
            <x-slot name="svg">
                <x-svg.bookmark/>
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Make Reservation') }}</h4>
            <div class="text-gray-500"></div>
        </x-data-card>

        @foreach($announcements as $announcement)
            <x-data-card :href="$announcement->hasURL() ? url($announcement->url) : null"
                         colorStyle="background-color: {{ $announcement->color }}">
                <x-slot name="svg">
                    <x-svg.speaker />
                </x-slot>

                <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 ">
                    {{ $announcement->title }}
                </h4>
                <div class="text-gray-500 dark:text-gray-300">
                    <p x-data="{ isCollapsed: false, maxLength: 62,
                             originalContent: '', content: '' }"
                            x-init="originalContent = $el.firstElementChild.textContent.trim();
                             content = originalContent.slice(0, maxLength); content += ((originalContent.length > content.length) ? '...' : '')"
                    >
                        <span x-text="isCollapsed ? originalContent : content" x-bind:class="isCollapsed ? 'text-lg' : ''"
                              class="whitespace-pre-line leading-7 tracking-wide transition-all duration-500">
                            {{ $announcement->body }}
                        </span>
                        <button class="focus:outline-none text-blue-400 px-2"
                                @click="$event.preventDefault(); isCollapsed = !isCollapsed"
                                x-show="originalContent.length > maxLength"
                                x-text="isCollapsed ? '{{ __('Show less') }}' : '{{ __('Show more') }}'"
                        ></button>
                    </p>

                </div>

                @can('announcements.edit')
                    <div class="flex items-center justify-end text-blue-500 text-white text-underline mt-2">
                        <a class="flex items-center space-x-1 space-x-reverse" href="{{ url("/announcements/$announcement->id/edit") }}">
                            <x-svg.edit size="w-3 h-3" />
                            {{ __('Edit') }}
                        </a>
                    </div>
                @endcan
            </x-data-card>
        @endforeach

        @can('tickets.view')
            @foreach($currentEvents as $currentEvent)
                <x-data-card :href="url('/tickets?event=' . $currentEvent->id)" color="bg-green-400">
                    <x-slot name="svg">
                        <x-svg.clock/>
                    </x-slot>

                    <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        {{ $currentEvent->description }}
                    </h4>
                </x-data-card>
            @endforeach
        @endcan

        @if($periods)
            @foreach($shownTypes as $type)
                <x-data-card class="relative"
                        colorStyle="background-color: {{ $type->color }}"
                             x-data="{ current: {{ $periods->count()-1 }}, count: {{ $periods->count() }} }">

                    <x-slot name="head">
                        @if($periods->count() > 1)
                            <div class="absolute flex justify-between items-center h-full w-full">
                                <button @click="current--" :disabled="current <= 0"
                                        class="bg-gray-100 opacity-75 dark:bg-gray-600
                                         ltr:-translate-x-2 rtl:translate-x-2 transform
                                 disabled:text-gray-500 dark:disabled:text-gray-500 disabled:cursor-default
                                  rounded-full p-2 transition-dark focus:outline-none">
                                    <x-svg.chevron-left class="rtl:rotate-180 transform" />
                                </button>
                                <button @click="current++" :disabled="current >= count-1"
                                        class="bg-gray-100 opacity-75 dark:bg-gray-600 rounded-full transform
                                         rtl:-translate-x-2 ltr:translate-x-2
                                disabled:text-gray-500 dark:disabled:text-gray-500 disabled:cursor-default
                                 p-2 transition-dark focus:outline-none">
                                    <x-svg.chevron-right class="rtl:rotate-180 transform" />
                                </button>
                            </div>
                        @endif
                    </x-slot>

                    <x-slot name="svg">
                        <x-svg.ticket/>
                    </x-slot>

                    <ol class="items-center"
                        >
                        @php($i = 0)
                        @foreach($periods->reverse() as $period)
                            <li x-show="current === {{ $i }}"
                                x-transition:enter="transition transition-opacity duration-700"
                                x-transition:enter-start="opacity-50"
                                x-transition:enter-end="opacity-100"
                                style="{{ $i != $periods->count()-1 ? 'display:none;' : '' }}">
                                <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                                    @if($type->isUnlimited())
                                        {{ $type->locale_plural_name }}
                                    @else
                                        {{ $tickets[$period->id][$type->id] }}
                                    @endif
                                </h4>

                                <div class="text-gray-500 dark:text-gray-300">
                                    @if($type->isUnlimited())
                                        {{ __('You can reserve') }}
                                    @else
                                        {{ __(':type reservations left between :start and :end', [
                                            'start' => $period->start->translatedFormat('l d M'),
                                            'end' => $period->end->translatedFormat('l d M'),
                                            'type' => $type->locale_plural_name,
                                        ]) }}
                                    @endif
                                </div>
                            </li>
                            @php($i++)
                        @endforeach
                    </ol>
                </x-data-card>
            @endforeach
        @endif

        @if(auth()->user()->isDeacon())
            <x-data-card color="bg-blue-500">
                <x-slot name="svg">
                    <x-svg.user class="text-white" />
                </x-slot>

                <h4 class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ __('Deacon') }}</h4>
            </x-data-card>
        @endif

        {{--        <x-data-card color="bg-gray-800">--}}
        {{--            <x-slot name="svg">--}}
        {{--                <x-svg.ticket />--}}
        {{--            </x-slot>--}}

        {{--            <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $baskhaTickets }}</h4>--}}
        {{--            <div class="text-gray-500 dark:text-gray-300">{{ __('Baskha reservations left') }}</div>--}}
        {{--        </x-data-card>--}}

        {{--        <x-data-card color="bg-gray-800">--}}
        {{--            <x-slot name="svg">--}}
        {{--                <x-svg.ticket />--}}
        {{--            </x-slot>--}}

        {{--            <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $baskhaOccasionTickets }}</h4>--}}
        {{--            <div class="text-gray-500 dark:text-gray-300">{{ __('Baskha Mass reservations left') }}</div>--}}
        {{--        </x-data-card>--}}

        <x-data-card color="bg-yellow-500"
                     data-step="1"
                     data-intro="{{ __('This is your ID Number which you can use to reserve by phone') }}">
            <x-slot name="svg">
                <x-svg.id/>
            </x-slot>

            <h4 class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $num->format(auth()->user()->id) }}</h4>
            <div class="text-gray-500 dark:text-gray-300">{{ __('Your ID Number') }} #</div>
        </x-data-card>

        @if($user->isAdmin())
            <x-data-card color="bg-blue-500"
                         x-data="{ number: '{{ $num->format($users) }}' }"
                         @user-created.window="number = $event.detail.number"
            >
                <x-slot name="svg">
                    <x-svg.users class="text-white"/>
                </x-slot>

                @push('scripts')
                    <script>
                        Echo.private('user.created')
                            .listen('UserRegistered', (response) => {

                                window.dispatchEvent(new CustomEvent('user-created', {
                                    detail: {
                                        @if(app()->getLocale() === 'ar')
                                        number: englishToArabicNumbers(response.usersCount.toString())
                                        @else
                                        number: response.usersCount
                                        @endif
                                    }
                                }));

                            });
                    </script>
                @endpush

                <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200" x-text="number">{{ $num->format($users) }}</h4>
                <div class="text-gray-500 dark:text-gray-300">{{ __('Total Users') }}</div>
            </x-data-card>

            <x-data-card color="bg-green-500">
                <x-slot name="svg">
                    <x-svg.users class="text-white"/>
                </x-slot>

                <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    {{ $num->format($verified_users) }}
                </h4>
                <div class="text-gray-500 dark:text-gray-300">{{ __('Verified Users') }}</div>
            </x-data-card>
        @endif
    </div>

    @can('viewStatistics')
        <div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-6 p-4">
            <div class="bg-gray-200 rounded-md col-span-1">
                <x-charts.users-status />
            </div>

            <div class="bg-gray-200 rounded-md col-span-2">
                <x-charts.logins />
            </div>
        </div>
    @endcan

    @if($user->isUser() && !empty($only) && !$user->isActive())
        @if(collect($only)->contains('user.name'))
        <span class="flex justify-center text-3xl font-bold text-red-500">
            {{ __('Please write your full name (' . config('settings.full_name_number') . ' names)') }}
        </span>
        @endif
        @if(collect($only)->contains('user.national_id'))
            <span class="flex justify-center text-3xl font-bold text-red-500 mt-2">
            {{ __('Please write your national ID number') }}
        </span>
        @endif

        <livewire:users.user-form :user="$user" :only="$only"/>
    @endif

</x-master>
