@props([
    'class' => null,
    'color' => 'bg-blue-500 hover:bg-blue-600 focus:bg-blue-600',
    'disabled' => false,
])

<button class="flex px-4 py-2 rounded-lg {{ $color }}
        transition-colors duration-300 ease-in
        text-white focus:outline-none {{ $class }}" {{ $disabled ? 'disabled="disabled"' : '' }}
        {{ $attributes }}>
    @isset($svg)
        <div class="{{ $slot->toHtml() ? 'ltr:mr-2 rtl:ml-2' : '' }}">
            {{ $svg }}
        </div>
    @endisset

    {{ $slot }}
</button>