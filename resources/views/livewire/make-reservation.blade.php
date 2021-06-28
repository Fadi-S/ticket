<div>
    <x-slot name="title">Make Reservation | Ticket</x-slot>

    <x-card>
        <form
                x-data="{}"
                x-init="window.livewire.on('set:event', (event) => { $dispatch('openconfirmation') })"
                wire:submit.prevent="save" action="{{ url('/reservations') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div class="flex space-x-2 rtl:space-x-reverse justify-between sm:justify-start">
                    <div>
                        @if(auth()->user()->can('create', \App\Models\User\User::class))
                            <x-button id="open-user-btn" type="button" @click="$dispatch('openuser')" wire:key="create-user-button"
                                      color="bg-green-400 hover:bg-green-500 dark:bg-green-500 dark:hover:bg-green-600 text-white">
                                <x-slot name="svg">
                                    <x-svg.add/>
                                </x-slot>
                                {{ __("Create New User") }}
                            </x-button>
                        @endif
                    </div>

                    <div>
                        @if(auth()->user()->can('createGuests'))
                            <x-button id="open-guest-btn" type="button" wire:key="guest-user-button"
                                      color="bg-indigo-400 hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white"
                                      @click="$dispatch('openguest')">
                                <x-slot name="svg">
                                    <x-svg.add/>
                                </x-slot>
                                {{ __("Reserve For a Guest") }}
                            </x-button>
                        @endif
                    </div>
                </div>

                <div x-data="{ searching: false }"
                     data-step="4"
                     data-title="{{ __('Search users you want to add to the reservation') }}"
                     data-intro="{{ __('You can search by phone number, email, name or id by putting this sign ~+id ex: ~2') }}"
                     @click.away="searching=false"
                     @keydown.escape="searching=false; document.querySelector('#user-search').blur()"
                     class="max-w-2xl mx-auto">
                    <label id="listbox-label" class="block text-sm font-medium text-gray-700">
                        {{ __("Search Users") }}
                    </label>
                    <div class="mt-1 relative">
                        <div>
                            <input type="text" @focus="searching=true"
                                   wire:model.debouce.300ms="search"
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
                                <x-svg.search/>
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

                                            <span dir="ltr" class="mx-2 truncate transition-colors duration-500
                                                {{ $selected ? 'font-bold text-indigo-200' : 'font-normal group-hover:text-indigo-200 text-gray-500 dark:text-gray-200' }}">
                                                #{{ $user->id }}
                                            </span>

                                            <span class="hidden md:block transition-colors duration-500
                                                        {{ $selected ? 'font-bold text-white' : 'font-normal group-hover:text-white dark:text-gray-200' }} mx-2 truncate">
                                              {{ $user->name }}
                                            </span>

                                            <span class="hidden md:block transition-colors duration-500
                                                    {{ $selected ? 'font-bold text-white' : 'font-normal group-hover:text-white dark:text-gray-200' }} mx-2 truncate">
                                              {{ $user->arabic_name }}
                                            </span>

                                            <span class="mx-2 truncate md:hidden block transition-colors duration-500
                                            {{ $selected ? 'font-bold text-white' : 'font-normal group-hover:text-white dark:text-gray-200' }}">
                                                {{ $user->locale_name }}
                                            </span>

                                            <span dir="ltr" class="mx-2 truncate transition-colors duration-500
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
                                                <x-svg.check/>
                                          </span>
                                        @endif

                                    </li>

                                @empty
                                    <li x-state:on="Highlighted"
                                        x-state:off="Not Highlighted"
                                        id="user-search-0" role="option"
                                        class="cursor-default select-none bg-white dark:bg-gray-700
                                         transition-colors duration-500
                                         relative py-2 pl-3 pr-9 text-gray-900 z-10">
                                        <div class="flex">
                                            <div class="font-normal truncate flex flex-col
                                            space-y-2 md:space-y-0
                                            md:flex-row md:justify-between
                                             w-full items-center" x-data="{  }">
                                                <span class="dark:text-gray-100">{{ __("No users match search :search", ['search' => $search]) }}</span>
                                                @if(auth()->user()->can('create', \App\Models\User\User::class))
                                                    <x-button id="open-user-btn" type="button" @click="$dispatch('openuser')"
                                                              color="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white">
                                                        <x-slot name="svg">
                                                            <x-svg.add/>
                                                        </x-slot>
                                                        {{ __("Create New User") }}
                                                    </x-button>
                                                @endif
                                            </div>
                                        </div>

                                    </li>
                                @endforelse

                            </ul>
                        </div>
                    </div>
                </div>

                @if($users->isNotEmpty())
                    <div class="max-w-4xl mx-auto">
                        @if($redirectAfterReservation)
                            <button type="button" wire:click="clearUsers"
                                    class="flex items-center rounded-md px-4 py-2 bg-blue-500 hover:bg-blue-600 transition-dark mb-4 focus:outline-none">
                                <div class="ltr:mr-2 rtl:ml-2">
                                    <x-svg.refresh wire:target="clearUsers" wire:loading.remove />
                                    <x-svg.spinner wire:target="clearUsers" wire:loading />
                                </div>
                                {{ __('New Reservation') }}
                            </button>
                        @endif

                        <x-table.table data-step="5" class="max-w-3xl"
                                       data-intro="{{ __('These are the users that you chose to reserve for them') }}"
                                       wire:loading.class="opacity-50" wire:target="toggleUser, removeUser">
                            <x-slot name="head">
                                <tr>
                                    <x-table.th>{{ __("ID") }}</x-table.th>
                                    <x-table.th>{{ __("Name") }}</x-table.th>
                                    {{--                                <x-table.th>{{ __("National ID") }}</x-table.th>--}}
                                    <x-table.empty-th>Remove User</x-table.empty-th>
                                </tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($users as $user)
                                    <tr id="user-selected-{{ $user['id'] }}">
                                        <x-table.td dir="ltr" class="text-center">#{{ $user['id'] }}</x-table.td>
                                        <x-table.td className="space-y-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $user['arabic_name'] }}
                                            </div>
                                            <div class="dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                                {{ $user['national_id'] ?? '' }}
                                            </div>
                                            @if(! config('settings.arabic_name_only'))
                                                <div class="dark:text-gray-400 font-semibold text-gray-500 text-sm">
                                                    {{ $user['name'] }}
                                                </div>
                                            @endif
                                        </x-table.td>

                                        {{--                                    <x-table.td>{{ $user['national_id'] ?? '-' }}</x-table.td>--}}

                                        <x-table.td>
                                            <x-buttons.cancel
                                                    data-step="6"
                                                    data-intro="{{ __('To remove a user form this reservation you can click this button') }}"
                                                    wire:click="removeUser('{{ $user['id'] }}')"/>
                                        </x-table.td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-table.table>
                    </div>
                @endif

                <x-layouts.errors />

                <div wire:ignore id='calendar' class="z-0"
                     data-step="7"
                     data-intro="{{ __('These are the list of events you can book a ticket in, you have to chose one of them by clicking on it') }}"
                ></div>

                <x-button type="submit" class="mx-auto mt-2"
                          data-step="8"
                          data-intro="{{ __('Then click on this button to save the reservation') }}"
                >
                    <x-slot name="svg">
                        <x-svg.edit wire:loading.remove wire:target="save"/>

                        <x-svg.spinner wire:loading wire:target="save"/>
                    </x-slot>
                    {{ __("Make Reservation") }}
                </x-button>
            </div>

            @push('modals')
                <x-layouts.modal
                        @open.window="open=true;
                         message=$event.detail.message;
                         title=$event.detail.title;
                         type=$event.detail.type;
                         color=(type == 'success' ? 'bg-green-100' : 'bg-red-100');">
                    <x-slot name="svg">
                        <svg x-show="type == 'error'" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>

                        <x-svg.check size="h-6 w-6" class="text-green-600" x-show="type == 'success'" />
                    </x-slot>

                    <x-slot name="body">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 flex items-center sm:justify-start sm:mr-3 justify-center"
                            x-text="title"
                            id="modal-headline">
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-300" x-text="message"></p>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <div class="space-x-2 flex justify-between">
                            <x-button class="mx-2" type="button" @click="open = false;"
                                      color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       text-gray-900 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                                {{ __("Close") }}
                            </x-button>
                        </div>
                    </x-slot>
                </x-layouts.modal>

                <x-layouts.modal @openConfirmation.window="open=true; message=$event.detail" color="bg-green-100">
                    <x-slot name="svg">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>

                    </x-slot>

                    <x-slot name="body">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 flex items-center sm:justify-start sm:mr-3 justify-center" id="modal-headline">
                            {{ __('Reservation Confirmation') }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                {{ __('Are you sure you want to reserve?') }}
                            </p>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <div class="space-x-2 flex justify-between">
                            <x-button class="mx-2" type="button"
                                      @click="window.livewire.emit('reserve'); open = false;"
                                      color="bg-green-500 hover:bg-green-600
                                       dark:bg-green-600 dark:hover:bg-green-700
                                        text-white">
                                <x-slot name="svg">
                                    <x-svg.check/>
                                </x-slot>

                                {{ __("Reserve") }}
                            </x-button>

                            <x-button class="mx-2" type="button" @click="open = false;"
                                      color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       text-gray-900 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                                {{ __("Close") }}
                            </x-button>
                        </div>
                    </x-slot>
                </x-layouts.modal>

                @if(auth()->user()->can('create', \App\Models\User\User::class))
                    <x-layouts.modal id="user-form-modal" :force="true" size="w-full rounded-none sm:rounded-lg md:max-w-2xl
                 lg:max-w-4xl my-2 sm:max-w-xl" @openUser.window="open=true" @closeUser.window="open=false">
                        <x-slot name="dialog">
                            <div class="px-6 py-10">
                                <livewire:users.user-form :card="false"/>
                            </div>
                        </x-slot>

                        <x-slot name="footer">
                            <div class="space-x-2 flex flex-row-reverse">

                                <x-button class="mx-2" type="button" @click="open = false;"
                                          color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       text-gray-900 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                                    {{ __("Cancel") }}
                                </x-button>
                            </div>
                        </x-slot>
                    </x-layouts.modal>
                @endif

                @if(auth()->user()->can('createGuests'))
                    <x-layouts.modal id="guest-form-modal" :force="true" size="w-full rounded-none sm:rounded-lg md:max-w-2xl
                 lg:max-w-4xl my-2 sm:max-w-xl" @openGuest.window="open=true" @closeGuest.window="open=false">
                        <x-slot name="dialog">
                            <div class="px-6 py-10">
                                <livewire:add-guest/>
                            </div>
                        </x-slot>

                        <x-slot name="footer">
                            <div class="space-x-2 flex flex-row-reverse">

                                <x-button class="mx-2" type="button" @click="open = false;"
                                          color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       text-gray-900 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                                    {{ __("Cancel") }}
                                </x-button>
                            </div>
                        </x-slot>
                    </x-layouts.modal>
                @endif
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
