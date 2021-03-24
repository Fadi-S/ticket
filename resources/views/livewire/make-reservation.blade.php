<div>
    <x-slot name="title">Make Reservation | Ticket</x-slot>

    <x-card>
        <form wire:submit.prevent="save" action="{{ url('/reservations') }}" method="POST">
            @csrf

            <div class="space-y-6">

                <div x-data="{ searching: false }"
                     @click.away="searching=false"
                     @keydown.escape="searching=false; document.querySelector('#user-search').blur()"
                     class="max-w-2xl mx-auto">
                    <label id="listbox-label" class="block text-sm font-medium text-gray-700">
                        {{ __("Search Users") }}
                    </label>
                    <div class="mt-1 relative">
                        <div>
                            <input type="text" @focus="searching=true"
                                   wire:model="search"
                                   dir="auto" autocomplete="off"
                                   @input="searching = ($event.target.value !== '')"
                                   name="user-search" id="user-search"
                                   class="relative w-full bg-white border border-gray-300 rounded-md
                                   dark:bg-gray-600 dark:border-gray-500
                                    dark:placeholder-gray-400 transition-colors duration-500
                                    dark:text-white
                             shadow-sm pl-3 pr-5 py-2 text-left focus:outline-none
                              focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                   focus:outline-none block w-full sm:text-sm rounded-md"
                                   placeholder="{{ __("Search") }}">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor"
                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <div x-show="searching" style="display: none;"
                             x-transition:enter="transition ease-in duration-100"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                                class="absolute mt-1 w-full rounded-md bg-white dark:bg-gray-700 shadow-lg">
                            <ul x-ref="listbox" tabindex="-1" role="listbox"
                                aria-labelledby="listbox-label"
                                class="max-h-60 rounded-md py-1 text-base ring-1 no-scrollbar
                                 ring-black ring-opacity-5 overflow-auto z-50
                                 bg-white dark:bg-gray-700 transition-colors duration-500
                                 focus:outline-none sm:text-sm">

                                @forelse($usersSearched as $user)
                                    @php
                                        $selected = in_array($user->id, $users->pluck('id')->toArray());
                                    @endphp

                                    <li x-state:on="Highlighted" id="user-search-{{ $user->id }}"
                                        x-state:off="Not Highlighted"
                                        wire:click="toggleUser('{{ $user->username }}')"
                                        role="option"
                                        class="group cursor-default z-10
                                         select-none relative py-2 pl-3 pr-9
                                        {{ $selected ? 'bg-indigo-600' : 'hover:text-white hover:bg-indigo-400
                                                                            bg-white dark:hover:bg-indigo-600 dark:bg-gray-700
                                                                             transition-colors duration-500' }}
                                        text-gray-900">
                                        <div class="flex">

                                            <span class="mx-2 truncate
                                                {{ $selected ? 'font-bold text-indigo-200' : 'font-normal group-hover:text-indigo-200 text-gray-500 dark:text-gray-200' }}">
                                                {{ $user->id }}
                                            </span>

                                            <span class="hidden md:block
                                                        {{ $selected ? 'font-bold text-white' : 'font-normal group-hover:text-white dark:text-gray-200' }} mx-2 truncate">
                                              {{ $user->name }}
                                            </span>

                                            <span class="hidden md:block
                                                    {{ $selected ? 'font-bold text-white' : 'font-normal group-hover:text-white dark:text-gray-200' }} mx-2 truncate">
                                              {{ $user->arabic_name }}
                                            </span>

                                            <span class="mx-2 truncate md:hidden block
                                            {{ $selected ? 'text-indigo-200' : 'group-hover:text-indigo-200 text-gray-500 dark:text-gray-200' }}">
                                                {{ $user->locale_name }}
                                            </span>

                                            <span dir="ltr" class="mx-2 truncate hidden md:block
                                            {{ $selected ? 'text-indigo-200' : 'group-hover:text-indigo-200 text-gray-500 dark:text-gray-200' }}">
                                                {{ '@' . $user->username }}
                                            </span>

                                            <span dir="ltr" class="mx-2 truncate
                                             {{ $selected ? 'text-indigo-200' : 'group-hover:text-indigo-200 text-gray-500 dark:text-gray-200' }}">
                                                {{ $user->phone }}
                                            </span>

{{--                                            <span class="mx-2 truncate hidden md:block--}}
{{--                                             {{ $selected ? 'text-indigo-200' : 'group-hover:text-indigo-200 text-gray-500 dark:text-gray-200' }}">--}}
{{--                                                {{ $user->national_id }}--}}
{{--                                            </span>--}}
                                        </div>

                                        @if($selected)
                                          <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-white">
                                                <x-svg.check />
                                          </span>
                                        @endif

                                    </li>

                                @empty
                                    <li x-state:on="Highlighted"
                                        x-state:off="Not Highlighted"
                                        id="user-search-0" role="option"
                                        class="cursor-default select-none bg-white relative py-2 pl-3 pr-9 text-gray-900 z-10">
                                        <div class="flex">
                                            <div class="font-normal truncate flex flex-col
                                            space-y-2 md:space-y-0
                                            md:flex-row md:justify-between
                                             w-full items-center" x-data="{  }">
                                                <span>{{ __("No users match search :search", ['search' => $search]) }}</span>
                                                <x-button id="open-user-btn" type="button" @click="$dispatch('openuser')"
                                                          color="bg-green-500 hover:bg-green-600">
                                                    <x-slot name="svg">
                                                        <x-svg.add />
                                                    </x-slot>
                                                    {{ __("Create New User") }}
                                                </x-button>
                                            </div>
                                        </div>

                                    </li>
                                @endforelse

                            </ul>
                        </div>
                    </div>
                </div>

                @if($users->isNotEmpty())
                    <x-table.table wire:loading.class="opacity-50" wire:target="toggleUser, removeUser">
                        <x-slot name="head">
                            <tr>
                                <x-table.th>{{ __("ID") }}</x-table.th>
                                <x-table.th>{{ __("Name") }}</x-table.th>
                                <x-table.th>{{ __("Arabic Name") }}</x-table.th>
                                <x-table.th class="hidden md:table-cell">{{ __("Username") }}</x-table.th>
{{--                                <x-table.th>{{ __("National ID") }}</x-table.th>--}}
                                <x-table.th class="hidden md:table-cell">{{ __("Email") }}</x-table.th>
                                <x-table.th>{{ __("Phone") }}</x-table.th>
                                <x-table.empty-th>Remove User</x-table.empty-th>
                            </tr>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($users as $user)
                                <tr id="user-selected-{{ $user['id'] }}">
                                    <x-table.td>{{ $user['id'] }}</x-table.td>
                                    <x-table.td>{{ $user['name'] }}</x-table.td>
                                    <x-table.td>{{ $user['arabic_name'] }}</x-table.td>
                                    <x-table.td dir="ltr" class="rtl:text-right hidden md:table-cell">{{ '@' . $user['username'] }}</x-table.td>
{{--                                    <x-table.td>{{ $user['national_id'] ?? '-' }}</x-table.td>--}}
                                    <x-table.td class="hidden md:table-cell">{{ $user['email'] ?? '-' }}</x-table.td>
                                    <x-table.td dir="ltr" class="rtl:text-right">{{ $user['phone'] ?? '' }}</x-table.td>
                                    <x-table.td>
                                        <x-button type="button" color="rounded-full bg-red-500 hover:bg-red-600"
                                                  wire:click="removeUser('{{ $user['id'] }}')">
                                            <x-slot name="svg">
                                                <x-svg.x/>
                                            </x-slot>
                                        </x-button>
                                    </x-table.td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-table.table>
                @endif

                <div wire:ignore id='calendar' class="z-0"></div>

                <x-button type="submit" class="mx-auto mt-2">
                    <x-slot name="svg">
                        <x-svg.edit wire:loading.remove wire:target="save"/>

                        <x-svg.spinner wire:loading wire:target="save"/>
                    </x-slot>
                    {{ __("Make Reservation") }}
                </x-button>

                <x-layouts.errors/>
            </div>

            @push('modals')
                <x-layouts.modal @open.window="open=true; message=$event.detail">
                    <x-slot name="svg">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </x-slot>

                    <x-slot name="body">
                        <h3  class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                            {{ __("Couldn't reserve in this event") }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-300" x-text="message"></p>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <div class="space-x-2 flex flex-row-reverse">
                            <x-button class="mx-2" type="button" @click="open = false;"
                                      color="bg-white text-gray-700 hover:bg-gray-50 border border-gray-400">
                                {{ __("Close") }}
                            </x-button>
                        </div>
                    </x-slot>
                </x-layouts.modal>

                <x-layouts.modal id="user-form-modal" :force="true" size="w-full rounded-none sm:rounded-lg md:max-w-2xl
                 lg:max-w-4xl my-2 sm:max-w-xl" @openUser.window="open=true" @closeUser.window="open=false">
                    <x-slot name="dialog">
                        <livewire:users.user-form />
                    </x-slot>

                    <x-slot name="footer">
                        <div class="space-x-2 flex flex-row-reverse">

                            <x-button class="mx-2" type="button" @click="open = false;"
                                      color="bg-white dark:bg-gray-300 bg:text-gray-900 text-gray-700 hover:bg-gray-50 border border-gray-400">
                                {{ __("Cancel") }}
                            </x-button>
                        </div>
                    </x-slot>
                </x-layouts.modal>
            @endpush

            @push('header')
                <meta name="turbolinks-visit-control" content="reload">
            @endpush

            @push("scripts")

                <script>
                    window.locale = '{{ app()->getLocale() }}';
                </script>

                <script defer src="{{ mix('/js/reservation.js') }}"></script>
                <link href="{{ mix('/css/reservation.css') }}" rel="stylesheet"/>

            @endpush

        </form>
    </x-card>

</div>
