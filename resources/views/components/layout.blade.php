<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield("title")
    <title>{{ $title ?? env("APP_NAME") }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @livewireScripts

</head>

<body id="page-top">
<!-- component -->
<div>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
             class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
             class="fixed z-30 inset-y-0 left-0 w-56 transition duration-300 transform bg-brand overflow-y-auto
              lg:translate-x-0 lg:static lg:inset-0">

            <a href="{{ url('/') }}">
                <div class="flex items-center justify-center">
                    <div class="flex items-center">
                        <img class="img-circle" src="{{ url("images/logo-text-350x250.png") }}" alt="" width="100">
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

                <x-navbar.list label="Books">

                    <x-slot name="svg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255
                            0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669
                             0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5
                         14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5
                         4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                        </svg>
                    </x-slot>

                    <x-navbar.child label="Add Book" href="{{ url('/books/create') }}"
                                    active="{{ url()->current() == url('/books/create') }}"/>

                    <x-navbar.child label="View Books" href="{{ url('/books') }}"
                                    active="{{ url()->current() == url('/books') }}"/>

                </x-navbar.list>

                <x-navbar.list label="Sections">

                    <x-slot name="svg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                            <path fill-rule="evenodd"
                                  d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </x-slot>

                    <x-navbar.child label="Add Section" href="{{ url('/sections/create') }}"
                                    active="{{ url()->current() == url('/sections/create') }}"/>

                    <x-navbar.child label="View Sections" href="{{ url('/sections') }}"
                                    active="{{ url()->current() == url('/sections') }}"/>

                </x-navbar.list>

                <x-navbar.list label="Questions">

                    <x-slot name="svg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343
                                  4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994
                                   1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot>

                    <x-navbar.child label="Add Question" href="{{ url('/questions/create') }}"
                                    active="{{ url()->current() == url('/questions/create') }}"/>

                    <x-navbar.child label="View Questions" href="{{ url('/questions') }}"
                                    active="{{ url()->current() == url('/questions') }}"/>

                </x-navbar.list>

                <x-navbar.divider/>

                <x-navbar.list label="Moderators">
                    <x-slot name="svg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3
                            0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5
                            5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </x-slot>

                    <x-navbar.child label="Add Moderator" href="{{ url('/admins/create') }}"
                                    active="{{ url()->current() == url('/admins/create') }}"/>

                    <x-navbar.child label="View Moderators" href="{{ url('/admins') }}"
                                    active="{{ url()->current() == url('/admins') }}"/>

                </x-navbar.list>

                <x-navbar.list label="Roles">
                    <x-slot name="svg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106
                               2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836
                                1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533
                                0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6
                                 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10
                                  13a3 3 0 100-6 3 3 0 000 6z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </x-slot>

                    <x-navbar.child label="Add Role" href="{{ url('/roles/create') }}"
                                    active="{{ url()->current() == url('/roles/create') }}"/>

                    <x-navbar.child label="View Roles" href="{{ url('/roles') }}"
                                    active="{{ url()->current() == url('/roles') }}"/>

                </x-navbar.list>

                <x-navbar.link label="Activity Log" :href="url('/activity')"
                               :active="url()->current() == url('/activity')">
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
                                {{ auth("admin")->user()->name }}
                            </div>
                            <div class="relative block h-10 w-10 border border-gray-400 overflow-hidden rounded-full">
                                <img class="h-full w-full object-cover"
                                     src="{{ auth("admin")->user()->picture  }}"
                                     alt="Profile Pic">
                            </div>
                        </button>

                        <div x-show="dropdownOpen" @click="dropdownOpen = false"
                             class="fixed inset-0 h-full w-full z-10"
                             style="display: none;"></div>

                        <div x-show.transition="dropdownOpen"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10"
                             style="display: none;">
                            <a href="{{ url("/admins/" . auth('admin')->user()->username) }}"
                               class="block px-4 py-2 text-sm
                               {{ url("/admins/" . auth('admin')->user()->username) == url()->current() ? "bg-indigo-600 text-white" : "text-gray-700" }}
                                       hover:bg-indigo-600 hover:text-white">Profile</a>

                            <a href="{{ url("/admins/change-password") }}"
                               class="block px-4 py-2 text-sm
                               {{ url("/admins/change-password") == url()->current() ? "bg-indigo-600 text-white" : "text-gray-700" }}
                                       hover:bg-indigo-600 hover:text-white">Change
                                Password</a>

                            <form action="{{ url('/logout') }}" method="POST" class="w-full justify-start">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-sm text-red-500
                                 hover:bg-red-600 flex items-center justify-start
                                  hover:text-white focus:outline-none space-x-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto">

                {{ $slot ?? "" }}

            </main>
        </div>
    </div>
</div>

<script src="{{ url('js/app.js') }}"></script>
@livewireStyles

</body>

</html>
