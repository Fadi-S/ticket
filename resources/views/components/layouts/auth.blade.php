<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ $title ?? 'Sign In to Ticket' }}</title>
    <link href="{{ url("css/app.css") }}" rel="stylesheet">
    <script src="{{ url("js/turbo.js") }}"></script>

    @livewireStyles

</head>
<body class="app {{ $isDark ? 'dark' : '' }}"
      :class="{'dark': dark}"
      x-data="{ dark: '{{ $isDark }}' }"
      @dark.window="dark = $event.detail.enable">

<div class="dark:text-white min-h-screen flex items-center
 justify-center dark:bg-gray-800 transition-colors duration-500
 bg-gray-200 py-12 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">

        <div class="bg-white dark:bg-gray-700
         transition-colors duration-500
        sm:rounded-xl shadow-2xl flex sm:flex-row
        flex-col justify-between overflow-hidden">
            <div class="pb-10/12 sm:pb-2/3 relative sm:w-1/2">
                <img class="opacity-0 absolute dark:opacity-100
                 transition-all duration-700
                 h-full object-cover object-top
                 sm:object-center w-full" src="{{ url("/images/jesus-500.jpg") }}"
                     alt="Jesus Christ">

                <img class="absolute dark:opacity-0
                transition-all duration-700
                 h-full object-cover object-top
                 sm:object-center w-full" src="{{ url("/images/stgeorge_bg-500.jpg") }}"
                     alt="Saint George">
            </div>

            <div class="m-4 sm:w-1/2 sm:my-auto my-4 py-6">
                <div class="items-center w-full flex flex-col mb-6 space-y-6">
                    <x-form.night-switch />

                    <x-language-dropdown />
                </div>


                {{ $slot }}
            </div>

        </div>
    </div>
</div>

@livewireScripts
<script src="{{ url("js/app.js") }}" data-turbolinks-eval="false" data-turbo-eval="false"></script>
@stack('scripts')
</body>
</html>
