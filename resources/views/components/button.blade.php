@props([
    'class' => null,
    'color' => 'bg-blue-500 dark:bg-blue-700
     hover:bg-blue-600 dark:hover:bg-blue-800
      focus:bg-blue-600 dark:focus:bg-blue-900 text-white',
    'disabled' => false,
])

<button class="flex items-center px-4 py-2 rounded-xl {{ $color }}
        transition-colors duration-300 ease-in
        focus:outline-none {{ $class }}" {{ $disabled ? 'disabled="disabled"' : '' }}
        {{ $attributes }}>
    @isset($svg)
        <div class="{{ $slot->toHtml() ? 'ltr:mr-2 rtl:ml-2' : '' }}">
            {{ $svg }}
        </div>
    @endisset

    {{ $slot }}
</button>
