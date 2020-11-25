<x-master>
    <x-slot name="title">
        Ticket's Dashboard
    </x-slot>

    <div class="m-4 grid items-start grid-cols-4 gap-4">
        <x-data-card color="bg-blue-400">
            <x-slot name="svg">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700">{{ $users }}</h4>
            <div class="text-gray-500">Total Users</div>
        </x-data-card>

        <x-data-card color="bg-green-400">
            <x-slot name="svg">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                          clip-rule="evenodd"></path>
                </svg>
            </x-slot>

            <h4 class="text-2xl font-semibold text-gray-700">{{ $events }}</h4>
            <div class="text-gray-500">Total Events</div>
        </x-data-card>
    </div>

</x-master>
