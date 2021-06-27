@props([
    'class' => '',
    'size' => 'w-6 h-6',
])

<svg {{ $attributes }} class="{{ $size }} {{ $class }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
</svg>
