@props(['class' => '', 'label' => '', 'size' => 'w-full', 'checked' => null, 'id', 'options', 'multiple'=>false])

<div class="flex flex-col {{ $size }}">
    @isset($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endisset

    <select dir="ltr" class="mt-1 focus:outline-none focus:border-blue-500 p-2 focus:border-3
    dark:bg-gray-600 dark:border-gray-500
     block w-full sm:text-sm border-gray-300 rounded-md h-10 border {{ $class }}" {{ $multiple ? 'multiple' : '' }}
            id="{{ $id }}" {{ $attributes }}>

        @foreach($options as $key => $option)
            <option value="{{ $key }}"
                    @if($multiple)
                    {{ in_array($key, $checked) ? 'selected' : '' }}
                    @else
                    {{ (old($attributes['name']) ?? $checked) == $key ? 'checked' : '' }}
                    @endif
            >
                {{ $option }}
            </option>
        @endforeach

    </select>

</div>