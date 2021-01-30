<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ url("/css/app.css") }}" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ $title ?? 'Ticket' }}</title>

    <script src="{{ url("js/turbo.js") }}"></script>
    @livewireScripts
    <script src="{{ url("js/app.js") }}"></script>
</head>
<body class="app">

<div>
    <div x-data="{ sidebarOpen: true, intentional: false }"
         x-init="sidebarOpen = document.body.clientWidth > 1024;
            window.addEventListener('resize', () =>
                sidebarOpen = intentional ? sidebarOpen : window.innerWidth > 1024
            );"
         class="flex h-screen bg-gray-200">
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false; intentional=true;"
             class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

        <div :class="sidebarOpen ? 'translate-x-0 ease-out lg:translate-x-0 lg:static lg:inset-0' : '-translate-x-full ease-in'"
             class="fixed z-30 inset-y-0 left-0 w-56 transition duration-150 transform bg-gray-800 overflow-y-auto">

            <a href="{{ url('/') }}">
                <div class="flex items-center justify-center">
                    <div class="flex items-center m-4">
                        <img class="rounded-full w-1/4" src="{{ url("assets/stg_logo-250.png") }}" alt="">
                        <span class="w-3/4 text-gray-200 ml-6 text-2xl font-semibold">Ticket</span>
                    </div>
                </div>
            </a>

            <nav class="mt-4 space-y-4">

                <x-navbar.link label="Home" :href="url('/')">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0
                            001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0
                            001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001
                            1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                </x-navbar.link>

                <x-navbar.divider/>

                <x-navbar.link label="Make Reservation" data-turbolinks="false" :href="url('/reservations/create')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </x-navbar.link>

                <x-navbar.link label="Tickets" :href="url('/tickets')"
                               :active="url()->current() == url('/tickets')">
                    <x-svg.ticket/>
                </x-navbar.link>

                @can("users.create" || "users.edit" || "users.view")
                    <x-navbar.divider/>

                    <x-navbar.list label="Users">

                        <x-slot name="svg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </x-slot>

                        @can("users.create")
                            <x-navbar.child label="Add User" href="{{ url('/users/create') }}"/>
                        @endcan

                        @can("users.view")
                            <x-navbar.child label="View Users" href="{{ url('/users') }}"/>
                        @endcan

                    </x-navbar.list>
                @endcan

                @can("tickets.view")
                    <x-navbar.list label="Masses">

                        <x-slot name="svg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                        </x-slot>

                        @can("events.create")
                            <x-navbar.child label="Add Mass" href="{{ url('/masses/create') }}"/>
                        @endcan

                        <x-navbar.child label="View Masses" href="{{ url('/masses') }}"/>

                    </x-navbar.list>
                @endcan

                @if(false)
                    @can("tickets.view")
                        <x-navbar.list label="Kiahk">

                            <x-slot name="svg">
                                <x-svg.christmas/>
                            </x-slot>

                            @can("events.create")
                                <x-navbar.child label="Add Kiahk" href="{{ url('/kiahk/create') }}"/>
                            @endcan

                            <x-navbar.child label="View Kiahk Events" href="{{ url('/kiahk') }}"/>

                        </x-navbar.list>
                    @endcan
                @endif

                @if(auth()->user()->isUser())
                    <x-navbar.link label="Friends" :href="url('/friends')">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4
                            4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0
                             00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004
                             15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </x-navbar.link>
                @endif

                @can('activityLog')
                    <x-navbar.divider/>

                    <x-navbar.link label="Activity Log" :href="url('/logs')">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1
                               1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </x-navbar.link>
                @endcan

                <x-navbar.divider/>

                <x-navbar.link class="text-red-400 text-lg" label="Sign Out" :href="url('/logout')">
                    <x-svg.logout/>
                </x-navbar.link>

            </nav>
        </div>
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center py-4 px-6 bg-white shadow-md">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen; intentional=true;"
                            class="text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center">

                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = ! dropdownOpen"
                                class="flex items-center justify-center space-x-2 focus:outline-none">
                            <div class="text-gray-700 font-light text-sm">
                                {{ auth()->user()->name }}
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
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
                             style="display: none;">
                            <a href="{{ url("/users/" . auth()->user()->username) }}" data-turbolinks="false"
                               class="block px-4 py-2 text-sm
                               {{ url("/users/" . auth()->user()->username) == url()->current() ? "bg-gray-200" : "text-gray-700" }}
                                       hover:bg-gray-200 ">Profile</a>

                            <a href="{{ url('logout') }}" class="w-full px-4 py-2 text-sm text-red-500
                                 hover:bg-gray-200 flex items-center justify-start
                                   focus:outline-none space-x-2">
                                <x-svg.logout/>
                                <span>Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">

                {{ $slot ?? "" }}

            </main>
            <footer class="bg-white text-gray-400 text-sm py-4 px-2 hidden md:flex">
                <span class="mx-auto">
                    Copyright © <a class="text-blue-300 text-underline" href="https://fadisarwat.dev">Fadi Sarwat</a>, StGeorge Sporting 2021</span>
            </footer>
        </div>
    </div>
</div>


@include("flash")

@stack("scripts")

@stack('modals')

@livewireStyles
</body>
</html>
