@props([
'label', 'href',
])

<a class="flex items-center text-sm font-semibold
        text-gray-300 dark:text-gray-300 hover:bg-gray-500 rounded-lg py-2 px-2
        bg-opacity-25 {{ ($href == url()->current()) ? 'active' : '' }}
        hover:bg-opacity-25" href="{{ $href }}">
    {{ $slot }}

    <span class="mx-3">{{ $label }}</span>
</a>
