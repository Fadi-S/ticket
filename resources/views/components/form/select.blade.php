@props(['class' => '', 'label' => '', 'size' => 'w-full', 'checked' => null, 'id', 'options'])

<div class="flex flex-col {{ $size }}">
    @isset($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endisset

    <select class="mt-1 focus:outline-none focus:border-blue-500 p-2 focus:border-3
     block w-full sm:text-sm border-gray-300 rounded-md h-10 border {{ $class }}"
            id="{{ $id }}" {{ $attributes }}>

        @foreach($options as $key => $option)
            <option value="{{ $key }}" {{ (old($attributes['name']) ?? $checked) == $key ? 'checked' : '' }}>{{ $option }}</option>
        @endforeach

    </select>

</div>