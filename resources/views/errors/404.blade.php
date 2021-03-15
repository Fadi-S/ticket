<x-dynamic-component :component="auth()->check() ? 'master' : 'layouts.auth'">

    <x-slot name="title">
        {{ __('Oops! this page :url isn\'t found here', ['url' => '']) }}
    </x-slot>

    <div class="flex items-center justify-center h-full">
        <h1 class="text-2xl">
            {!! __('Oops! this page :url isn\'t found here', ['url' => '<span class="font-bold">'. request()->path() . '</span>']) !!}
        </h1>
    </div>
</x-dynamic-component>