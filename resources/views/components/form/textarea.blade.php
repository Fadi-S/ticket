@props([
    'class' => '',
    'label' => '',
    'size' => 'w-full',
    'id',
    'value' => '',
    'error' => null,
])

<div class="flex flex-col {{ $size }}">
    @isset($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endisset

    <textarea class="shadow-sm focus:outline-none focus:border-blue-500
    focus:border-2 dark:bg-gray-600 transition-dark
    dark:placeholder-gray-400 focus:ring-blue-600
    focus:ring-1 placeholder-gray-500 border-gray-300 dark:border-gray-500
    mt-1 block w-full sm:text-sm border py-2 px-2
    border-gray-300 rounded-md {{ $class }}" rows="8"
              id="{{ $id }}" {{ $attributes }}>{{ $value }}</textarea>

</div>
