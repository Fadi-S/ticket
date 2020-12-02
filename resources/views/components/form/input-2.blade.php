@props([
    'class' => null,
    'label',
    'id',
])

<div>
    <label for="{{ $id }}" class="sr-only">{{ $label }}</label>
    <input id="{{ $id }}" name="{{ $id }}" {{ $attributes }}
           class="appearance-none h-12
                           relative block w-full px-3 py-2 border
                            border-gray-300 placeholder-gray-500
                             text-gray-900 rounded-lg focus:outline-none
                              focus:ring-blue-600 focus:ring-1
                              focus:z-10 sm:text-sm"
           placeholder="{{ $label }}">
</div>