@props([
    'class' => null,
])

<svg {{ $attributes }} class="h-6 w-6 transform duration-500 transition-all {{ $class }}"
     fill="currentColor" xmlns="http://www.w3.org/2000/svg"
     xmlns:xlink="http://www.w3.org/1999/xlink">
    <path d="M9 9c1.29 0 2.5.41 3.47 1.11L17.58 5H13V3h8v8h-2V6.41l-5.11
                    5.09c.7 1 1.11 2.2 1.11 3.5a6 6 0 0 1-6 6a6 6 0 0 1-6-6a6 6 0 0
                    1 6-6m0 2a4 4 0 0 0-4 4a4 4 0 0 0 4 4a4 4 0 0 0 4-4a4 4 0 0 0-4-4z"/>
    <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)"/>
</svg>
