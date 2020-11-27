<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Sign In to Ticket' }}</title>
    <link href="{{ url("css/app.css") }}" rel="stylesheet">
</head>
<body class="app">

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">

        {{ $slot }}

    </div>
</div>

<script src="{{ url("js/app.js") }}"></script>
</body>
</html>
