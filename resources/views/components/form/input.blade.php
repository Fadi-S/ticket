@props([
    'class' => '',
    'label' => '',
    'size' => 'w-full',
    'id',
    'error' => null,
    'value' => null,
    'dir' => __('ltr'),
])

<div class="{{ $size }}">
    <div class="flex flex-col">
        @isset($label)
            <label for="{{ $id }}" class="dark:text-white rtl:text-right">{{ $label }}</label>
        @endisset

        <div class="relative">
            <input class="mt-1 focus:outline-none px-2
            dark:bg-gray-600 transition-dark
            {{ $error
            ? 'focus:ring-red-500 ring-red-500 focus:ring-1 border-red-500 placeholder-red-300 dark:border-red-600 text-red-900'
            : 'dark:placeholder-gray-400 focus:ring-blue-600 focus:ring-1 placeholder-gray-500 border-gray-300 dark:border-gray-500' }}
                    dark:text-white
                     block w-full sm:text-sm rounded-md h-10 border {{ $class }}"
                   dir="{{ $dir }}"
                   id="{{ $id }}" value="{{ old($attributes['name']) ?? $value }}" {{ $attributes }} />

            @if($error)
                <div class="absolute inset-y-0 {{ $dir == 'ltr' ? 'right-0 pr-3' : 'left-0 pl-3' }}
                        flex items-center pointer-events-none">
                    <x-svg.exclamation-circle color="text-red-500" />
                </div>
            @endif
        </div>

    </div>

    @if($error)
        @foreach($error as $e)
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $e }}</p>
        @endforeach
    @endif
</div>
