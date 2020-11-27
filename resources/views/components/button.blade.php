@props([
    'class' => null,
    'color' => 'bg-blue-500 hover:bg-blue-600'
])

<button class="flex px-4 py-2 rounded-lg {{ $color }}
 text-white focus:outline-none {{ $class }}"
        {{ $attributes }}>
    @isset($svg)
        <div class="{{ !$slot ? 'mr-2' : '' }}">
            {{ $svg }}
        </div>
    @endisset

    {{ $slot }}
</button>