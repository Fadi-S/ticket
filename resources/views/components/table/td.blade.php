@props([
    'class' => 'px-6 py-4 whitespace-nowrap dark:text-white',
    'className' => null,
])

<td {{ $attributes }} class="{{ $class }} {{ $className }}">
    {{ $slot }}
</td>
