@props([
    'class' => null,
])

<th class="px-6 py-3 bg-gray-100 ltr:text-left rtl:text-right text-xs
transition-colors duration-500 dark:bg-gray-900
font-medium text-gray-500 dark:text-gray-300
 uppercase tracking-wider {{ $class }}">
    {{ $slot }}
</th>
