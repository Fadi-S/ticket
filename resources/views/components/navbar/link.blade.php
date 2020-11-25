@props([
    'label', 'href', 'active' => false,
])

<a class="flex items-center text-gray-100 hover:bg-gray-700 py-2 px-4 bg-opacity-25 {{ $active ? 'active' : '' }}
        hover:bg-opacity-25" href="{{ $href }}">
    {{ $slot }}

    <span class="mx-3 font-semibold">{{ $label }}</span>
</a>
