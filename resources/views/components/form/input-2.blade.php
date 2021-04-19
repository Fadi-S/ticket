@props([
    'class' => null,
    'error' => null,
    'label',
    'id',
    'dir' => __('ltr'),
])

<div>
    <label for="{{ $id }}" class="sr-only">{{ $label }}</label>
    <div class="mt-1 relative rounded-md shadow-sm">
    <input id="{{ $id }}" name="{{ $id }}" {{ $attributes }}
           class="appearance-none h-12
           dark:bg-gray-600 dark:text-white text-gray-900
        {{ $error
            ? 'focus:ring-red-500 ring-red-500 focus:ring-1 border-red-500 placeholder-red-300 dark:border-red-600 text-red-900'
            : 'dark:placeholder-gray-400 focus:ring-blue-600 focus:ring-1 placeholder-gray-500 border-gray-300 dark:border-gray-500' }}
            transition-dark
              block w-full px-3 py-2 border
                 rounded-lg focus:outline-none
                 focus:z-10 sm:text-sm" dir="{{ $dir }}"
           placeholder="{{ $label }}">

        @if($error)
            <div class="absolute inset-y-0 {{ $dir == 'ltr' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                <x-svg.exclamation-circle color="text-red-500" />
            </div>
        @endif
    </div>

    @if($error)
        @foreach($error as $e)
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $e }}</p>
        @endforeach
    @endif
</div>
