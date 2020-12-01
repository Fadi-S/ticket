<x-master>
    <x-slot name="title">
        Ticket's Dashboard
    </x-slot>

    <div class="m-4 grid items-start grid-cols-4 gap-4">

        <x-data-card color="bg-indigo-400">
            <x-slot name="svg">
                <x-svg.ticket />
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700">{{ $massTickets }}</h4>
            <div class="text-gray-500">Mass reservations left in {{ \Carbon\Carbon::now()->monthName }}</div>
        </x-data-card>

        <x-data-card color="bg-blue-400">
            <x-slot name="svg">
                <x-svg.ticket />
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700">{{ $kiahkTickets }}</h4>
            <div class="text-gray-500">Kiahk reservations left</div>
        </x-data-card>

        @if(auth()->user()->hasRole('super-admin'))
            <x-data-card color="bg-blue-400">
                <x-slot name="svg">
                    <x-svg.users class="text-white" />
                </x-slot>

                <h4 class="text-2xl font-semibold text-gray-700">{{ $users }}</h4>
                <div class="text-gray-500">Total Users</div>
            </x-data-card>

            <x-data-card color="bg-green-400">
                <x-slot name="svg">
                    <x-svg.calendar class="text-white" />
                </x-slot>

                <h4 class="text-2xl font-semibold text-gray-700">{{ $events }}</h4>
                <div class="text-gray-500">Total Events</div>
            </x-data-card>
        @endif
    </div>

</x-master>
