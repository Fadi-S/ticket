@props([
    'color' => 'bg-gray-600',
    'colorStyle' => null,
    'class' => null,
    'href' => null,
])

<div class="w-full h-full col-span-12 sm:col-span-6 md:col-span-6 lg:col-span-4 xl:col-span-3
{{ $href ? 'cursor-pointer transform transition duration-300 hover:scale-95 focus:scale-95' : '' }}
{{ $class }}" {{ $attributes }}>
    @isset($head)
        {{ $head }}
    @endisset

    @if($href)
        <a href="{{ $href }}">
    @endif

        <div class="flex h-full items-center px-5 py-6 shadow-lg rounded-xl bg-white dark:bg-gray-700 transition-dark">
            <div class="p-3 rounded-full {{ $color }} bg-opacity-75" style="{{ $colorStyle }}">

                {{ $svg }}

            </div>

            <div class="mx-5 w-full {{ __('ltr') === 'rtl' ? 'font-tajawal' : 'font-work' }}">
                {{ $slot }}
            </div>
        </div>

    @if($href)
        </a>
    @endif
</div>
