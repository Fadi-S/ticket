<x-dynamic-component :component="auth()->check() ? 'master' : 'layouts.auth'">

    <x-slot name="title">
        Oops! An error occurred
    </x-slot>

    <div class="flex items-center justify-center h-full">
        <h1 class="text-2xl">
            An error occurred and we are working to fix it, please be patient!
        </h1>
    </div>
</x-dynamic-component>