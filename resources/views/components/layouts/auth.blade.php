<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Sign In to Ticket' }}</title>
    <link href="{{ url("css/app.css") }}" rel="stylesheet">
</head>

@livewireScripts

<body class="app">

<div class="min-h-screen flex items-center justify-center bg-gray-200 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">

        <div class="bg-white rounded-2xl shadow-2xl flex sm:flex-row flex-col justify-between overflow-hidden">
            <div class="pb-10/12 sm:pb-2/3 relative sm:w-1/2">
                <img class="absolute h-full object-cover object-top sm:object-center w-full" src="{{ url("/images/stgeorge_bg-500.jpg") }}" alt="Saint George">
            </div>

            <div class="m-4 sm:w-1/2 sm:my-auto my-4">
                {{ $slot }}
            </div>

        </div>
    </div>
</div>

@livewireStyles

<script src="{{ url("js/app.js") }}"></script>
@stack('scripts')
</body>
</html>
