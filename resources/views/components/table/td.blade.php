@props([
    'class' => 'px-6 py-4 dark:text-white',
])

<td {{ $attributes }} class="{{ $class }}">
    {{ $slot }}
</td>
