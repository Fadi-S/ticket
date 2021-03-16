@props(['class' => '', 'label' => '', 'size' => 'w-full', 'id', 'value' => null])

<div class="flex flex-col {{ $size }}">
    @isset($label)
        <label for="{{ $id }}" class="dark:text-white">{{ $label }}</label>
    @endisset

    <input class="mt-1 focus:outline-none
    dark:bg-gray-600 dark:border-gray-500
    dark:placeholder-gray-400 transition-colors duration-500
    focus:border-blue-500 p-2 focus:border-3
    dark:text-white
     block w-full sm:text-sm border-gray-300 rounded-md h-10 border {{ $class }}"
           id="{{ $id }}" value="{{ old($attributes['name']) ?? $value }}" {{ $attributes }} />

</div>