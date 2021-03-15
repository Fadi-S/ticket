@props([
    'class' => null,
])

<td {{ $attributes }} class="px-6 py-4 whitespace-nowrap dark:text-white {{ $class }}">
    {{ $slot }}
</td>