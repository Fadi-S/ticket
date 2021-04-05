<x-master>
    <x-slot name="title">
        Ticket's Dashboard
    </x-slot>

    <div class="m-4 grid items-start grid-cols-4 gap-4">
        <x-data-card :href="url('/reserve')" color="bg-red-400"
                     data-step="3"
                     data-intro="{{ __('You can click here to reserve') }}"
                     class="cursor-pointer transform transition duration-150 hover:scale-95 focus:scale-95">
            <x-slot name="svg">
                <x-svg.bookmark />
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Make Reservation') }}</h4>
            <div class="text-gray-500"></div>
        </x-data-card>

        @can('tickets.view')
            @if($currentEvent)
                <x-data-card :href="url('/tickets?event=' . $currentEvent->id)" color="bg-green-400"
                             class="cursor-pointer transform transition duration-150
                              hover:scale-95 focus:scale-95">
                    <x-slot name="svg">
                        <x-svg.clock />
                    </x-slot>

                    <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Current Event') }}</h4>
                 </x-data-card>
            @endif
        @endcan

        <x-data-card color="bg-indigo-400"
                     data-step="2" data-intro="{{ __('Here is the number of masses left in this month') }}">
            <x-slot name="svg">
                <x-svg.ticket />
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $massTickets }}</h4>
            <div class="text-gray-500 dark:text-gray-300">{{ __('Mass reservations left in :Month', ['month' => \Carbon\Carbon::now()->monthName]) }}</div>
        </x-data-card>


        <x-data-card color="bg-yellow-400"
                     data-step="1"
                     data-intro="{{ __('This is your ID Number which you can use to reserve by phone') }}">
            <x-slot name="svg">
                <x-svg.id />
            </x-slot>

            <h4 class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $num->format(auth()->user()->id) }}</h4>
            <div class="text-gray-500 dark:text-gray-300">{{ __('Your ID Number') }} #</div>
        </x-data-card>

        @if($user->isAdmin())
            <x-data-card color="bg-blue-400"
                         x-data="{ number: '{{ $num->format($users) }}' }"
                         @user-created.window="number = $event.detail.number"
            >
                <x-slot name="svg">
                    <x-svg.users class="text-white" />
                </x-slot>

                @push('scripts')
                <script>
                    Echo.private('user.created')
                        .listen('UserRegistered', (response) => {

                            window.dispatchEvent(new CustomEvent('user-created', { detail: {
                                @if(app()->getLocale() === 'ar')
                                    number: englishToArabicNumbers(response.usersCount.toString())
                                @else
                                    number: response.usersCount
                                @endif
                            } }));

                        });
                </script>
                @endpush

                <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200" x-text="number">{{ $num->format($users) }}</h4>
                <div class="text-gray-500 dark:text-gray-300">{{ __('Total Users') }}</div>
            </x-data-card>
        @endif
    </div>

    @if($user->isUser() && $user->hasFirstNameOnly())
        <span class="flex justify-center text-3xl font-bold text-red-500">
            {{ __('Please write your full name (3 names)') }}
        </span>

        <livewire:users.user-form :user="$user" :only="[ 'user.name', 'user.arabic_name' ]" />
    @endif

    @can('viewAgentDetails')
        <livewire:view-agents />
    @endcan

</x-master>
