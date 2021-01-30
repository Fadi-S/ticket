@props([
    'label', 'href', 'class' => null,
])

<a class="{{ $class }} flex items-center text-gray-100
 hover:bg-gray-700 py-3 px-4 bg-opacity-25 {{ ($href == url()->current()) ? 'active' : '' }}
        hover:bg-opacity-25" href="{{ $href }}" {{ $attributes }}>
    {{ $slot }}

    <span class="mx-3 font-semibold">{{ $label }}</span>
</a>
