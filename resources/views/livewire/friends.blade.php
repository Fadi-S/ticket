<div>

    <x-slot name="title">
        Friends | Ticket
    </x-slot>

    <span class="dark:text-gray-300 flex font-bold justify-center m-4 text-gray-900 text-xl text-center">
            {{ __('Your friends can book a ticket for you and you can for them.') }}
    </span>

    <x-button class="mx-6" color="bg-green-500 dark:bg-green-600
             hover:bg-green-600 dark:hover:bg-green-700
              focus:bg-green-600 dark:focus:bg-green-700"
              type="button" @click="$dispatch('open')">
        <x-slot name="svg">
            <x-svg.add />
        </x-slot>
        {{ __('Add Friend') }}
    </x-button>

    <x-card>

        <span class="dark:text-gray-300 font-bold mb-4 text-gray-900 text-2xl">
                {{ __('Friends') }}
        </span>

        <x-table.table data-step="9"
                       data-intro="{{ __('These are your confirmed friends') }}">
            <x-slot name="head">
                <tr>
                    <x-table.th>{{ __('ID') }}</x-table.th>
                    <x-table.th>{{ __('Name') }}</x-table.th>
                    <x-table.th>{{ __('Handle') }}</x-table.th>
                    <x-table.th>{{ __('Unfriend') }}</x-table.th>
                </tr>
            </x-slot>

            <x-slot name="body">

                @forelse($friends as $user)
                    <tr>
                        <x-table.td>
                            <span dir="ltr" class="rtl:text-right text-gray-800 dark:text-gray-200 text-md font-semibold">#{{ $user->id }}</span>
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $user->picture }}" alt="{{ $user->arabic_name }}'s picture">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm dark:text-gray-300 font-medium text-gray-900">
                                        {{ $user->arabic_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>

                        <x-table.td>
                            <span dir="ltr" class="text-gray-800 dark:text-gray-300 text-md font-semibold">{{ '@' . $user->username }}</span>
                        </x-table.td>

                        <x-table.td>
                            <x-buttons.cancel wire:click="rejectFriend('{{$user->username }}')" />
                        </x-table.td>
                    </tr>
                @empty
                    <tr>
                        <x-table.td colspan="4">
                            {{ __('You have no friends yet.') }}
                        </x-table.td>
                    </tr>
                @endforelse

            </x-slot>

        </x-table.table>

        {{ $friends->links() }}

    </x-card>

    @if($requests->isNotEmpty())
        <x-card>

        <span class="dark:text-gray-300 font-bold mb-4 text-gray-900 text-2xl">
                {{ __('Friends Requests') }}
        </span>

            <x-table.table>
                <x-slot name="head">
                    <tr>
                        <x-table.th>{{ __('ID') }}</x-table.th>
                        <x-table.th>{{ __('Name') }}</x-table.th>
                        <x-table.th>{{ __('Handle') }}</x-table.th>
                        <x-table.th>{{ __('Confirm') }}</x-table.th>
                        <x-table.th>{{ __('Reject') }}</x-table.th>
                    </tr>
                </x-slot>

                <x-slot name="body">

                    @foreach($requests as $friendship)
                        @if(!$friendship->sender)
                            @continue
                        @endif

                        <tr>
                            <x-table.td>
                                <span dir="ltr" class="rtl:text-right text-gray-800 dark:text-gray-200 text-md font-semibold">#{{ $friendship->sender->id }}</span>
                            </x-table.td>

                            <x-table.td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $friendship->sender->picture }}" alt="{{ $friendship->sender->name }}'s picture">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm dark:text-gray-300 font-medium text-gray-900">
                                            {{ $friendship->sender->locale_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $friendship->sender->email }}
                                        </div>
                                    </div>
                                </div>
                            </x-table.td>

                            <x-table.td>
                                <span dir="ltr" class="text-gray-800 dark:text-gray-300 text-md font-semibold">{{ '@' . $friendship->sender->username }}</span>
                            </x-table.td>

                            <x-table.td>
                                <button class="rounded-full focus:outline-none p-3
                                         dark:bg-green-600 dark:hover:bg-green-700
                                         bg-green-400 hover:bg-green-500 text-green-100
                                          transition-dark"
                                        wire:click="confirmFriend('{{$friendship->sender->username }}')">
                                    <x-svg.check size="h-5 h-5"/>
                                </button>
                            </x-table.td>

                            <x-table.td>
                                <button class="rounded-full p-1 focus:outline-none
                                border border-red-600 hover:bg-red-100 text-red-500
                                dark:focus:text-red-200 dark:hover:text-red-200
                                    focus:bg-red-100 dark:focus:bg-red-500
                                     dark:hover:bg-red-500 transition-dark"
                                          wire:click="rejectFriend('{{$friendship->sender->username }}')">

                                    <x-svg.x size="h-5 h-5" />
                                </button>
                            </x-table.td>
                        </tr>
                    @endforeach

                </x-slot>

            </x-table.table>

            {{ $friends->links() }}

        </x-card>
    @endif

    <x-layouts.modal @open.window="open=true" @close.window="open=false">
        <x-slot name="dialog">
            <x-card>

                <form class="mb-4" wire:submit.prevent="addFriend" method="POST">
                    @csrf

                    <span class="text-sm mb-4 flex justify-start dark:text-gray-300 text-gray-600">
                        {{ __('By email, username or phone') }}
                    </span>

                    <x-form.input autocomplete="off" dir="ltr"
                                  name="search" id="search"
                                   wire:model="search" />

                    <x-button type="submit" class="mt-2" color="bg-green-500 hover:bg-green-600 focus:bg-green-600">
                        <x-slot name="svg">
                            <x-svg.add wire:loading.remove wire:target="addFriend" />

                            <x-svg.spinner wire:loading wire:target="addFriend" />
                        </x-slot>

                        {{ __('Add Friend') }}
                    </x-button>
                </form>

                @if(session()->has('error'))
                    <x-layouts.error size="w-full">{{ session()->get('error') }}</x-layouts.error>
                @endif

            </x-card>
        </x-slot>
    </x-layouts.modal>

</div>
