<x-master>
    <x-slot name="title">View All users | Ticket</x-slot>


    <x-card>
        <x-table.table>
            <x-slot name="head">
                <tr>
                    <x-table.th>Name</x-table.th>
                    <x-table.th>Username</x-table.th>
                    <x-table.th>Number</x-table.th>
                    <x-table.th>Role</x-table.th>
                    <x-table.empty-th>Edit</x-table.empty-th>
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($users as $user)
                    <tr>
                        <x-table.td>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $user->picture }}" alt="{{ $user->name }}'s picture">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>

                        <x-table.td>
                            <span class="text-gray-800 text-md font-semibold">{{ $user->username }}</span>
                        </x-table.td>

                        <x-table.td>
                            <span class="text-gray-800 text-md font-semibold">{{ $user->phone }}</span>
                        </x-table.td>

                        <x-table.td>
                            <span class="text-gray-800 text-md font-semibold">{{ $user->roles[0]->name }}</span>
                        </x-table.td>

                        <x-table.td>
                            <a class="bg-blue-400 px-4 py-2 hover:bg-blue-500
                             text-white text-md rounded-lg"
                               href="{{ url("/users/$user->username/edit") }}">Edit</a>
                        </x-table.td>
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $users->links() }}

    </x-card>

</x-master>
