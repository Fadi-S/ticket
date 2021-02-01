<div>

    <x-slot name="title">
        Friends | Ticket
    </x-slot>

    <x-card>

        <x-table.table>
            <x-slot name="head">
                <tr>
                    <x-table.th>Name</x-table.th>
                    <x-table.th>Handle</x-table.th>
                </tr>
            </x-slot>

            <x-slot name="body">

                @forelse($friends as $user)
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
                            <span class="text-gray-800 text-md font-semibold">{{ '@' . $user->username }}</span>
                        </x-table.td>
                    </tr>
                @empty
                    <tr>
                        <x-table.td colspan="2">
                            You have no friends yet,
                            <button type="button" class="text-blue-500 underline">
                                Add Friend
                            </button>
                        </x-table.td>
                    </tr>
                @endforelse

            </x-slot>

        </x-table.table>

        {{ $friends->links() }}

    </x-card>
</div>
