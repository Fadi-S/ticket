@props([
    'class' => '',
])

<button class="bg-blue-500 flex px-4 py-2 rounded-lg
 text-white hover:bg-blue-600 focus:outline-none {{ $class }}"
        {{ $attributes }}>
    @isset($svg)
        <div class="mr-2">
            {{ $svg }}
        </div>
    @endisset

    {{ $slot }}
</button>