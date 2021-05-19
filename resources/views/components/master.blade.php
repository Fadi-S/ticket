<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ __('ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ mix("/css/app.css") }}" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <title>{{ $title ?? 'Ticket' }}</title>

    @livewireStyles

    @stack('header')
</head>
<body class="app no-scrollbar {{ $isDark ? 'dark' : '' }}" id="app"
      :class="{'dark': dark}"
      x-data="{ dark: '{{ $isDark }}' }"
      @dark.window="dark = $event.detail.enable">

<x-notification/>

<div class="dark:text-white no-scrollbar">
    <div x-data="{ sidebarOpen: true, intentional: false }"
         x-init="sidebarOpen = document.body.clientWidth > 1024;
            window.addEventListener('resize', () =>
                sidebarOpen = intentional ? sidebarOpen : window.innerWidth > 1024
            );
             $refs.sidebar.classList.remove('ltr:-translate-x-full', 'rtl:translate-x-full', 'lg:rtl:translate-x-0', 'lg:ltr:translate-x-0');
            $refs.black.classList.remove('hidden');"
         class="flex h-screen bg-gray-100 dark:bg-gray-800 transition-colors duration-500">
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false; intentional=true;"
             class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden hidden" x-ref="black"></div>

        <div x-ref="sidebar" :class="sidebarOpen
          ? 'translate-x-0 ease-out lg:translate-x-0 lg:static lg:inset-0'
          : 'ltr:-translate-x-full rtl:translate-x-full ease-in'"

             class="fixed z-30 inset-y-0 rtl:right-0 ltr:left-0 w-56
             ltr:-translate-x-full rtl:translate-x-full lg:rtl:translate-x-0 lg:ltr:translate-x-0
              transition-all duration-150 transform h-screen
              bg-gray-800 dark:bg-gray-900 overflow-y-auto no-scrollbar">

            <a href="{{ url('/') }}">
                <div class="flex items-center justify-center">
                    <div class="flex items-center m-4">
                        <img class="rounded-full w-1/4" src="{{ asset("images/stg_logo-250.png") }}" alt="">
                        <span class="w-3/4 text-gray-200 ltr:ml-6 rtl:mr-6 text-2xl font-semibold">Ticket</span>
                    </div>
                </div>
            </a>

            <nav class="mt-4 space-y-4">

                <x-navbar.link label="{{ __('Home') }}" :href="url('/')">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0
                            001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0
                            001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001
                            1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                </x-navbar.link>

                <x-navbar.divider/>

                <x-navbar.link label="{{ __('Make Reservation') }}" data-turbolinks="false" :href="url('/reserve')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </x-navbar.link>

                <x-navbar.link label="{{ __('Tickets') }}" :href="url('/tickets')"
                               :active="url()->current() == url('/tickets')">
                    <x-svg.ticket/>
                </x-navbar.link>

                @can("users.*")
                    <x-navbar.divider/>

                    <x-navbar.list label="{{ __('Users') }}">

                        <x-slot name="svg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </x-slot>

                        @can("users.create")
                            <x-navbar.child label="{{ __('Add User') }}" href="{{ url('/users/create') }}"/>
                        @endcan

                        @can("users.view")
                            <x-navbar.child label="{{ __('View Users') }}" href="{{ url('/users') }}"/>
                        @endcan

                    </x-navbar.list>
                @endcan

                @can("tickets.view")
                    <x-navbar.divider />

                    <x-navbar.list label="{{ __('Periods') }}">

                        <x-slot name="svg">
                            <x-svg.calendar />
                        </x-slot>

                        @can("events.create")
                            <x-navbar.child label="{{ __('Add Period') }}" href="{{ url('/periods/create') }}"/>
                        @endcan

                        <x-navbar.child label="{{ __('View Periods') }}" href="{{ url('/periods') }}"/>

                    </x-navbar.list>

                    <x-navbar.list label="{{ __('Masses') }}">

                        <x-slot name="svg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                        </x-slot>

                        @can("events.create")
                            <x-navbar.child label="{{ __('Add Mass') }}" href="{{ url('/masses/create') }}"/>
                        @endcan

                        <x-navbar.child label="{{ __('View Masses') }}" href="{{ url('/masses') }}"/>

                    </x-navbar.list>

                    <x-navbar.list label="{{ __('Vespers') }}">

                        <x-slot name="svg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </x-slot>

                        @can("events.create")
                            <x-navbar.child label="{{ __('Add Vesper') }}" href="{{ url('/vespers/create') }}"/>
                        @endcan

                        <x-navbar.child label="{{ __('View Vespers') }}" href="{{ url('/vespers') }}"/>

                    </x-navbar.list>
                @endcan

{{--                @can("tickets.view")--}}
{{--                    <x-navbar.list label="{{ __('Baskhat') }}">--}}

{{--                        <x-slot name="svg">--}}
{{--                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>--}}
{{--                            </svg>--}}
{{--                        </x-slot>--}}

{{--                        @can("events.create")--}}
{{--                            <x-navbar.child label="{{ __('Add Baskha') }}" href="{{ url('/baskha/create') }}"/>--}}
{{--                        @endcan--}}

{{--                        <x-navbar.child label="{{ __('View Baskhat') }}" href="{{ url('/baskha') }}"/>--}}

{{--                    </x-navbar.list>--}}

{{--                    <x-navbar.list label="{{ __('Holy Week') }}">--}}

{{--                        <x-slot name="svg">--}}
{{--                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>--}}
{{--                            </svg>--}}
{{--                        </x-slot>--}}

{{--                        @can("events.create")--}}
{{--                            <x-navbar.child label="{{ __('Add Occasion') }}" href="{{ url('/holy/create') }}"/>--}}
{{--                        @endcan--}}

{{--                        <x-navbar.child label="{{ __('View Occasions') }}" href="{{ url('/holy') }}"/>--}}

{{--                    </x-navbar.list>--}}
{{--                @endcan--}}

                {{--                @can("tickets.view")--}}
                {{--                    <x-navbar.list label="{{ __('Kiahk') }}">--}}

                {{--                        <x-slot name="svg">--}}
                {{--                            <x-svg.christmas/>--}}
                {{--                        </x-slot>--}}

                {{--                        @can("events.create")--}}
                {{--                            <x-navbar.child label="{{ __('Add Kiahk') }}" href="{{ url('/kiahk/create') }}"/>--}}
                {{--                        @endcan--}}

                {{--                        <x-navbar.child label="{{ __('View Kiahk Events') }}" href="{{ url('/kiahk') }}"/>--}}

                {{--                    </x-navbar.list>--}}
                {{--                @endcan--}}

                @if(auth()->user()->users()->count() && !auth()->user()->can('users.view'))
                    <x-navbar.divider />

                    <x-navbar.link label="{{ __('Users') }}" :href="url('/users')">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </x-navbar.link>
                @endif

                @if(!auth()->user()->isAdmin())
                    @php($unconfirmed = auth()->user()->unconfirmedFriendRequests()->count())
                    <x-navbar.link label="{{ __('Friends') }}" :href="url('/friends')">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4
                            4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0
                             00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004
                             15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>

                        @if($unconfirmed)
                            <x-slot name="trailing">
                                <span class="flex items-center justify-center bg-red-500 rounded-full w-6 h-6 text-center">{{ $num->format($unconfirmed) }}</span>
                            </x-slot>
                        @endif
                    </x-navbar.link>
                @endif

                @can('activityLog')
                    <x-navbar.divider/>

                    <x-navbar.link label="{{ __('Activity Log') }}" :href="url('/logs')">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1
                               1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </x-navbar.link>
                @endcan

                <x-navbar.divider/>

                <x-language-dropdown/>

                <x-navbar.divider/>

                <form action="{{ url('/logout') }}" method="POST">
                    @csrf

                    <x-navbar.link type="submit" :button="true"
                                   class="text-red-400 text-lg"
                                   label="{{ __('Sign Out') }}">
                        <x-svg.logout/>
                    </x-navbar.link>
                </form>

            </nav>
        </div>
        <div class="flex-1 flex flex-col overflow-hidden no-scrollbar">
            <header class="flex justify-between items-center py-4 px-6 bg-white
            transition-colors duration-500
             dark:bg-gray-700 shadow-md">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen; intentional=true;"
                            class="text-gray-700 dark:text-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="material-icons sr-only">Menu Button</span>
                    </button>
                </div>

                <div class="flex items-center">

                    <x-button x-data="{}" color="bg-transparent text-gray-800 dark:text-gray-100"
                              @click="startIntro()" title="{{ __('Help') }}">
                        <x-slot name="svg">
                            <x-svg.help/>
                        </x-slot>
                    </x-button>

                    <div class="mx-2">
                        <x-form.night-switch/>
                    </div>

                    <div x-data="{ dropdownOpen: false }" class="relative mx-2">
                        <button @click="dropdownOpen = ! dropdownOpen"
                                class="flex items-center justify-center space-x-2 focus:outline-none">
                            <div class="flex items-center rtl:ml-2">
                                <div class="text-gray-700 dark:text-white text-sm font-semibold font-tajawal">
                                    {{ auth()->user()->first_name }}
                                </div>
                                @if(auth()->user()->isActive())
                                    <x-layouts.verified class="rtl:mr-2 ltr:ml-2" />
                                @endif
                            </div>
                            <div class="relative block h-10 w-10 border border-gray-400 overflow-hidden rounded-full">
                                <img class="h-full w-full object-cover"
                                     src="{{ auth()->user()->picture  }}"
                                     alt="Profile Pic">
                            </div>
                        </button>

                        <div x-show="dropdownOpen" @click="dropdownOpen = false"
                             class="fixed inset-0 h-full w-full z-10"
                             style="display: none;"></div>

                        <div x-show.transition="dropdownOpen"
                             class="absolute ltr:right-0 rtl:left-0 mt-2 w-48
                              bg-white dark:bg-gray-600
                              rounded-md overflow-hidden shadow-xl z-10"
                             style="display: none;">

                            <a href="{{ url("/users/" . auth()->user()->username) }}"
                               class="px-4 py-4 text-sm
                               {{ url("/users/" . auth()->user()->username) == url()->current() ? "bg-gray-200 dark:bg-gray-800" : "text-gray-700 dark:text-white" }}
                                       hover:bg-gray-200 dark:hover:bg-gray-800 flex items-center justify-start space-x-2">
                                <x-svg.user class="rtl:ml-2"/>
                                <span>{{ __('Profile') }}</span>
                            </a>

                            <form method="POST" action="{{ url('logout') }}">
                                @csrf

                                <button type="submit" class="w-full px-4 py-4 text-sm
                                 text-red-500 dark:text-red-400
                                 hover:bg-gray-200 dark:hover:bg-gray-800
                                 flex items-center justify-start
                                   focus:outline-none space-x-2">
                                    <x-svg.logout class="rtl:ml-2"/>
                                    <span>{{ __('Sign Out') }}</span>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto no-scrollbar">

                {{ $slot ?? "" }}

            </main>
            <footer class="bg-gray-200 dark:bg-gray-800 text-gray-500
            transition-colors duration-500
             dark:text-gray-400 text-sm py-2 px-2 hidden md:flex">
                <span dir="ltr" class="w-full flex items-center justify-center">
                    Copyright © <a class="text-blue-500 dark:text-blue-600 text-underline"
                       href="https://fadisarwat.dev">Fadi Sarwat</a>
                    @if(now()->month == 4 && now()->day == 25)
                        &nbsp;
                    <x-svg.cake />
                    @endif
                    , StGeorge Sporting 2021</span>

            </footer>
        </div>
    </div>
</div>

<script src="{{ mix("manifest.js") }}"></script>
@livewireScripts

<script src="{{ mix("vendor.js") }}"></script>
<script src="{{ mix("js/app.js") }}"></script>
@stack('charts')

@include("flash")

@stack("scripts")

@stack('modals')

</body>
</html>
