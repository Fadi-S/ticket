@props([
'label', 'href', 'active' => false,
])

<a class="flex items-center text-sm font-semibold
        text-gray-700 hover:bg-gray-500 rounded-lg py-2 px-2
        bg-opacity-25 {{ $active ? 'active' : '' }}
        hover:bg-opacity-25" href="{{ $href }}" {{ ($active) ? '@load=isOpen=false' : '' }}>
    {{ $slot }}

    <span class="mx-3">{{ $label }}</span>
</a>
