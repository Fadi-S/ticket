@props(['class' => '', 'label' => '', 'size' => 'w-full', 'id', 'value' => ''])

<div class="flex flex-col {{ $size }}">
    @isset($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endisset

    <textarea class="shadow-sm focus:outline-none focus:border-blue-500
    focus:border-2
    mt-1 block w-full sm:text-sm border py-2 px-4
    border-gray-300 rounded-md {{ $class }}"
              id="{{ $id }}" {{ $attributes }}>{{ $value }}</textarea>

</div>