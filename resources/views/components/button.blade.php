@props([
    'class' => null,
    'color' => 'bg-blue-500 hover:bg-blue-600 focus:bg-blue-600'
])

<button class="flex px-4 py-2 rounded-lg {{ $color }}
        transition-colors duration-200 ease-in
        text-white focus:outline-none {{ $class }}"
        {{ $attributes }}>
    @isset($svg)
        <div class="{{ $slot->toHtml() ? 'mr-2' : '' }}">
            {{ $svg }}
        </div>
    @endisset

    {{ $slot }}
</button>