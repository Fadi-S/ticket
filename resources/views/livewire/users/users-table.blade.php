<x-slot name="title">View All users | Ticket</x-slot>

<x-card>


    <div class="flex items-end">
        @if($searchByScout)
            <img class="w-10 h-10 mx-2" src="{{ asset('/images/algolia/algolia-blue-mark.svg') }}" alt="Search With Algolia">
        @endif

        <x-form.input name="search" :label="__('Search')"
                      dir="auto" autocomplete="off"
                      wire:model="search" id="search"
                      size="w-full lg:w-1/4 md:w-1/2"/>

        {{--        <x-button wire:click="$toggle('searchByScout')" class="mx-2">--}}
        {{--            <x-slot name="svg">--}}
        {{--                <x-dynamic-component :component="$searchByScout ? 'svg.eye' : 'svg.search'" />--}}
        {{--            </x-slot>--}}

        {{--            {{ __($searchByScout ? 'Enable' : 'Disable') }} {{ __('Exact Search') }}--}}
        {{--        </x-button>--}}
    </div>

    @php
        $colors = [
            'deacon-admin' => [
                'border' => 'border-indigo-500 dark:border-indigo-800',
                'background' => 'bg-indigo-200 dark:bg-indigo-800',
                'text' => 'text-indigo-500 dark:text-indigo-300',
            ],
            'super-admin' => [
                'border' => 'border-green-500 dark:border-green-800',
                'background' => 'bg-green-200 dark:bg-green-300',
                'text' => 'text-green-500 dark:text-green-800',
            ],
            'user' => [
                'border' => 'border-gray-500 dark:border-gray-300',
                'background' => 'bg-gray-200 dark:bg-gray-300',
                'text' => 'text-gray-500 dark:text-gray-800',
            ],
            'kashafa' => [
                'border' => 'border-yellow-500 dark:border-yellow-800',
                'background' => 'bg-yellow-200 dark:bg-yellow-800',
                'text' => 'text-yellow-700 dark:text-yellow-300',
            ],
            'agent' => [
                'border' => 'border-red-500 dark:border-red-300',
                'background' => 'bg-red-200 dark:bg-red-300',
                'text' => 'text-red-500 dark:text-red-800',
            ],
            'deacon' => [
                'border' => 'border-blue-500 dark:border-blue-800',
                'background' => 'bg-blue-200 dark:bg-blue-800',
                'text' => 'text-blue-500 dark:text-blue-300',
            ],
        ];
    @endphp

    <x-table.table>
        <x-slot name="head">
            <tr>
                <x-table.empty-th>{{ __('ID') }}</x-table.empty-th>
                <x-table.th>{{ __('Name') }}</x-table.th>
                <x-table.th class="hidden sm:table-cell">{{ __('Arabic Name') }}</x-table.th>
                <x-table.th>{{ __('Phone') }}</x-table.th>
                <x-table.th>{{ __('Created By') }}</x-table.th>
                {{--                    <x-table.th>{{ __('National ID') }}</x-table.th>--}}
                <x-table.th>{{ __('Role') }}</x-table.th>
                <x-table.th>{{ __('Edit') }}</x-table.th>
            </tr>
        </x-slot>

        <x-slot name="body">

            @forelse($users as $user)
                @php($color = $colors[isset($user->roles[0]) ? $user->roles[0]->name : 'user'])
                <tr wire:loading.class="opacity-50" wire:key="user-{{ $user->username }}">
                    <x-table.td>
                            <span dir="ltr" class="rtl:text-right text-gray-800 dark:text-gray-200 text-md font-semibold">#{{ $user->id }}</span>
                    </x-table.td>

                    <x-table.td>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ $user->picture }}" alt="{{ $user->name }}'s picture">
                            </div>
                            <div class="ml-4 space-y-2">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $user->name }}
                                </div>
                                <div class="dark:text-gray-300 font-semibold text-gray-500 text-sm sm:hidden">
                                    {{ $user->arabic_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>
                    </x-table.td>

                    <x-table.td class="hidden sm:table-cell">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $user->arabic_name }}
                        </div>
                    </x-table.td>

                    <x-table.td>
                        <div class="flex items-center justify-start">
                            @if($user->isVerified())
                                <div class=" rounded-full p-1 w-8">
                                    <x-svg.check class="text-green-500" size=""/>
                                </div>
                            @else
                                <div class="rounded-full p-1 w-8"></div>
                            @endif
                            <span dir="ltr" class="text-gray-800 dark:text-gray-200 text-md font-semibold mx-2">{{ $user->phone }}</span>
                        </div>
                    </x-table.td>

                    <x-table.td>
                        @if($user->creator)
                            <button type="button" wire:click="$set('search', '#{{ $user->creator->id }}')"
                                    title="#{{ $user->creator->id }}"
                                    class="text-gray-800 dark:text-gray-200 text-md focus:outline-none
                                    font-semibold hover:text-gray-700 dark:hover:text-gray-300">
                                {{ $user->creator->first_name }}
                            </button>
                        @else
                            -
                        @endif
                    </x-table.td>
                    {{--                        <x-table.td>--}}
                    {{--                            <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">{{ $user->national_id }}</span>--}}
                    {{--                        </x-table.td>--}}

                    <x-table.td>
                        <div class="flex items-center justify-start">
                            <div class="font-bold text-sm rounded-full py-1 px-2 text-center
                                    {{ $color['background'] . ' ' . $color['text'] }}">
                                {{ isset($user->roles[0]) ? $user->roles[0]->name : 'user' }}
                            </div>
                        </div>
                    </x-table.td>

                    <x-table.td>
                        <div class="flex items-center justify-start">
                            <a class="border-2 border-blue-500 dark:border-blue-800 transition-dark
                            dark:hover:bg-blue-700 flex hover:bg-blue-300
                             items-center justify-center p-2 rounded-full text-md text-white"
                               href="{{ url("/users/$user->username/edit") }}">

                                <x-svg.edit class="text-blue-500" />
                            </a>
                        </div>
                    </x-table.td>
                </tr>

            @empty
                <tr wire:key="user-0">
                    <td colspan="10">
                       <span class="flex justify-center mx-auto py-4 text-gray-600 dark:text-gray-300">
                            <x-svg.search color=""/> &nbsp; {{ __("No users match search :search", ['search' => $search]) }}
                       </span>
                    </td>
                </tr>
            @endforelse

        </x-slot>

    </x-table.table>

    {{ $users->links() }}

</x-card>
