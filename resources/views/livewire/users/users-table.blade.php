<x-slot name="title">View All users | Ticket</x-slot>

<x-card>


    <div class="flex">
{{--        <img class="w-8 h-8 mx-2" src="{{ asset('/images/algolia/algolia-blue-mark.svg') }}" alt="Search With Algolia">--}}

        <x-form.input name="search" :label="__('Search')"
                      dir="auto" autocomplete="off"
                      wire:model="search" id="search"
                      size="w-full lg:w-1/4 md:w-1/2" />
    </div>


    <x-table.table>
        <x-slot name="head">
            <tr>
                <x-table.th>{{ __('ID') }}</x-table.th>
                <x-table.th>{{ __('Name') }}</x-table.th>
                <x-table.th>{{ __('Arabic Name') }}</x-table.th>
                <x-table.th>{{ __('Username') }}</x-table.th>
                <x-table.th>{{ __('Phone') }}</x-table.th>
                <x-table.th>{{ __('Gender') }}</x-table.th>
{{--                    <x-table.th>{{ __('National ID') }}</x-table.th>--}}
                <x-table.th>{{ __('Role') }}</x-table.th>
                <x-table.empty-th>{{ __('Edit') }}</x-table.empty-th>
            </tr>
        </x-slot>

        <x-slot name="body">

            @forelse($users as $user)
                <tr wire:loading.class="opacity-50">
                    <x-table.td>
                        <span dir="ltr" class="rtl:text-right text-gray-800 dark:text-gray-200 text-md font-semibold">#{{ $user->id }}</span>
                    </x-table.td>

                    <x-table.td>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $user->picture }}" alt="{{ $user->name }}'s picture">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $user->name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>
                    </x-table.td>

                    <x-table.td>
                        <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">{{ $user->arabic_name }}</span>
                    </x-table.td>

                    <x-table.td>
                        <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">{{ $user->username }}</span>
                    </x-table.td>

                    <x-table.td>
                        <span dir="ltr" class="text-gray-800 dark:text-gray-200 text-md font-semibold">{{ $user->phone }}</span>
                    </x-table.td>

                    <x-table.td>
                        <span dir="ltr" class="text-gray-800 dark:text-gray-200 text-md font-semibold">{{ [__('Female'), __('Male')][$user->gender] ?? '-' }}</span>
                    </x-table.td>

{{--                        <x-table.td>--}}
{{--                            <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">{{ $user->national_id }}</span>--}}
{{--                        </x-table.td>--}}

                    <x-table.td>
                        <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">{{ isset($user->roles[0]) ? $user->roles[0]->name : 'user' }}</span>
                    </x-table.td>

                    <x-table.td>
                        <a class="bg-blue-400 hover:bg-blue-500
                        px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700
                         text-white text-md rounded-lg"
                           href="{{ url("/users/$user->username/edit") }}">{{ __('Edit') }}</a>
                    </x-table.td>
                </tr>

            @empty
                <tr>
                   <td colspan="8">
                       <span class="flex justify-center mx-auto py-4 text-gray-600 dark:text-gray-300">
                            <x-svg.search color="" /> &nbsp; {{ __("No users match search :search", ['search' => $search]) }}
                       </span>
                   </td>
                </tr>
            @endforelse

        </x-slot>

    </x-table.table>

    {{ $users->links() }}

</x-card>
