<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="icon" href="{{ config('settings.photo_library_url') . '/favicon.ico' }}" sizes="16x16">
    <title>{{ $title ?? 'Sign In to Ticket' }}</title>
    <link href="{{ mix("css/app.css") }}" rel="stylesheet">

    @livewireStyles

    @stack('header')
</head>
<body class="app {{ $isDark ? 'dark' : '' }}"
      :class="{'dark': dark}"
      x-data="{ dark: '{{ $isDark }}' }"
      @dark.window="dark = $event.detail.enable">
<div class="absolute top-0 items-center w-full flex bg-gray-100
 dark:bg-[#25303e] transition-dark py-2 px-4 justify-between">
    <x-language-dropdown textColor="text-gray-800 dark:text-white" />

    <div class="text-xs text-gray-400 dark:text-gray-500 sm:block hidden">
        <span dir="ltr" class="w-full flex items-center justify-center">
    Copyright © <a class="text-blue-400 dark:text-blue-800 text-underline"
                   href="https://fadisarwat.dev">Fadi Sarwat</a>
    @if(now()->month == 4 && now()->day == 25)
                &nbsp;
                <x-svg.cake />
            @endif
    , StGeorge Sporting 2021</span>
    </div>

    <x-form.night-switch />
</div>

<div class="dark:text-white min-h-screen flex flex-col items-center
  justify-center dark:bg-gray-800 transition-colors duration-500
 bg-gray-200">

    <div class="max-w-4xl w-full space-y-8 py-12 sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-700
         transition-colors duration-500
        sm:rounded-xl shadow-2xl flex sm:flex-row
        flex-col justify-between overflow-hidden">
            <div class="pb-10/12 sm:pb-2/3 relative sm:w-1/2">
                @php($twoPhotos = config('settings.light_theme_photo') !== config('settings.dark_theme_photo'))
                <img class="{{ $twoPhotos ? 'opacity-0 dark:opacity-100' : '' }} absolute
                 transition-all duration-700 z-0
                 h-full object-cover object-top
                 sm:object-center w-full" src="{{ config('settings.photo_library_url') . '/' . config('settings.dark_theme_photo') }}"
                     alt="Jesus Christ">

                @if($twoPhotos)
                <img class="absolute dark:opacity-0
                transition-all duration-700 z-0
                 h-full object-cover object-top
                 object-center w-full" src="{{ config('settings.photo_library_url') . '/' . config('settings.light_theme_photo') }}"
                     alt="Saint George">
                @endif
            </div>

            <div class="flex flex-col sm:w-1/2">
                @stack('actions')

                <div class="m-4 sm:my-auto my-4 py-6">

                    {{ $slot }}
                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ mix("manifest.js") }}"></script>
@livewireScripts
<script src="{{ mix("vendor.js") }}"></script>
<script src="{{ mix("js/app.js") }}"></script>
@stack('scripts')
</body>
</html>
