<x-dynamic-component :component="auth()->check() ? 'master' : 'layouts.auth'">

    <x-slot name="title">
        Oops! this page isn't found
    </x-slot>

    <div class="flex items-center justify-center h-full">
        <h1 class="text-2xl">
            Oops! this page <span class="font-bold">/{{ request()->path() }}</span> isn't found here
        </h1>
    </div>
</x-dynamic-component>