<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ url("/css/app.css") }}" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ $title ?? env('APP_NAME') }}</title>

    @livewireScripts

</head>
<body class="app">

<div>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
             class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
             class="fixed z-30 inset-y-0 left-0 w-56 transition duration-300 transform bg-gray-900 overflow-y-auto
              lg:translate-x-0 lg:static lg:inset-0">

            <a href="{{ url('/') }}">
                <div class="flex items-center justify-center">
                    <div class="flex items-center m-4">
                        <img class="rounded-full w-1/4" src="{{ url("images/logo.png") }}" alt="">
                        <span class="w-3/4 text-gray-200 ml-6 text-2xl font-semibold">Ticket</span>
                    </div>
                </div>
            </a>

            <x-navbar.divider/>

            <nav class="mt-4 space-y-4">

                <x-navbar.link label="Dashboard" :href="url('/')"
                               :active="url()->current() == url('/')">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0
                            001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0
                            001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001
                            1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                </x-navbar.link>

                <x-navbar.divider/>

                @can("reservation.create")
                    <x-navbar.link label="Make Reservation" :href="url('/reservations/create')"
                                   :active="url()->current() == url('/reservations/create')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </x-navbar.link>
                @endcan

                <x-navbar.divider/>

                @can("users.create" || "users.edit" || "users.view")
                    <x-navbar.list label="Users">

                        <x-slot name="svg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </x-slot>

                        <x-navbar.child label="Add User" href="{{ url('/users/create') }}"
                                        active="{{ url()->current() == url('/users/create') }}"/>

                        <x-navbar.child label="View Users" href="{{ url('/users') }}"
                                        active="{{ url()->current() == url('/users') }}"/>

                    </x-navbar.list>
                @endcan

                @can("masses.create" || "masses.edit" || "masses.view")
                    <x-navbar.list label="Masses">

                        <x-slot name="svg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                        </x-slot>

                        <x-navbar.child label="Add Mass" href="{{ url('/masses/create') }}"
                                        active="{{ url()->current() == url('/masses/create') }}"/>

                        <x-navbar.child label="View Masses" href="{{ url('/masses') }}"
                                        active="{{ url()->current() == url('/masses') }}"/>

                    </x-navbar.list>
                @endcan

                @can('admins.create' || 'admins.edit' || 'admins.view')
                <x-navbar.list label="Admins">
                    <x-slot name="svg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3
                            0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5
                            5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </x-slot>

                    <x-navbar.child label="Add Admin" href="{{ url('/admins/create') }}"
                                    active="{{ url()->current() == url('/admins/create') }}"/>

                    <x-navbar.child label="View Admins" href="{{ url('/admins') }}"
                                    active="{{ url()->current() == url('/admins') }}"/>

                </x-navbar.list>
                @endcan

                <x-navbar.divider/>

                <x-navbar.link label="Activity Log" :href="url('/logs')"
                               :active="url()->current() == url('/logs')">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1
                               1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                              clip-rule="evenodd"></path>
                    </svg>
                </x-navbar.link>

            </nav>
        </div>
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center py-4 px-6 bg-white shadow-md">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="text-gray-700 focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"></path>
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
                            <a href="{{ url("/admins/" . auth()->user()->username) }}"
                               class="block px-4 py-2 text-sm
                               {{ url("/admins/" . auth()->user()->username) == url()->current() ? "bg-indigo-600 text-white" : "text-gray-700" }}
                                       hover:bg-indigo-600 hover:text-white">Profile</a>

                            <a href="{{ url("/admins/change-password") }}"
                               class="block px-4 py-2 text-sm
                               {{ url("/admins/change-password") == url()->current() ? "bg-indigo-600 text-white" : "text-gray-700" }}
                                       hover:bg-indigo-600 hover:text-white">Change
                                Password</a>

                            <a href="{{ url('logout') }}" class="w-full px-4 py-2 text-sm text-red-500
                                 hover:bg-red-600 flex items-center justify-start
                                  hover:text-white focus:outline-none space-x-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">

                {{ $slot ?? "" }}

            </main>

            <footer class="bg-white text-gray-400 text-sm py-6 px-2 flex">
                <span class="mx-auto">Copyright © Fadi Sarwat, StGeorge Sporting 2020</span>
            </footer>
        </div>
    </div>
</div>

<script src="{{ url("js/app.js") }}"></script>
@include("flash")

@stack("scripts")

@livewireStyles
</body>
</html>
