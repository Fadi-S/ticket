<x-master>
    <x-slot name="title">
        Ticket's Dashboard
    </x-slot>

    <div class="m-4 grid items-start grid-cols-4 gap-4">


        <x-data-card :href="url('/reserve')" color="bg-red-400"
                     class="cursor-pointer transform transition duration-150 hover:scale-95 focus:scale-95">
            <x-slot name="svg">
                <x-svg.bookmark />
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700">{{ __('Make Reservation') }}</h4>
            <div class="text-gray-500"></div>
        </x-data-card>

        <x-data-card :href="url('/tickets?type=1')" color="bg-indigo-400"
                     class="cursor-pointer transform transition duration-150 hover:scale-95 focus:scale-95">
            <x-slot name="svg">
                <x-svg.ticket />
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700">{{ $massTickets }}</h4>
            <div class="text-gray-500">{{ __('Mass reservations left in :Month', ['month' => \Carbon\Carbon::now()->monthName]) }}</div>
        </x-data-card>


        <x-data-card color="bg-yellow-400">
            <x-slot name="svg">
                <x-svg.id />
            </x-slot>

            <h4 class="text-2xl font-bold text-gray-700">{{ $num->format(auth()->user()->id) }}</h4>
            <div class="text-gray-500">{{ __('Your ID Number') }}</div>
        </x-data-card>

        @if(auth()->user()->isAdmin())
            <x-data-card color="bg-blue-400">
                <x-slot name="svg">
                    <x-svg.users class="text-white" />
                </x-slot>

                <h4 class="text-2xl font-semibold text-gray-700">{{ $num->format($users) }}</h4>
                <div class="text-gray-500">{{ __('Total Users') }}</div>
            </x-data-card>

            <x-data-card color="bg-green-400">
                <x-slot name="svg">
                    <x-svg.calendar class="text-white" />
                </x-slot>

                <h4 class="text-2xl font-semibold text-gray-700">{{ $num->format($events) }}</h4>
                <div class="text-gray-500">{{ __('Total Upcoming Events') }}</div>
            </x-data-card>
        @endif
    </div>

</x-master>
