@props([
    'class' => '',
    'label' => '',
    'size' => 'w-full',
    'checked' => null,
    'id',
    'options',
    'multiple'=>false,
    'dir' => __('ltr'),
    'error' => null,
])

<div class="flex flex-col {{ $size }}">
    @isset($label)
        <label for="{{ $id }}" class="dark:text-white">{{ $label }}</label>
    @endisset

    <select dir="{{ $dir }}" class="mt-1 focus:outline-none p-2

    {{ $error
            ? 'focus:ring-red-500 ring-red-500 focus:ring-1 border-red-500 placeholder-red-300 dark:border-red-600 text-red-900'
            : 'dark:placeholder-gray-400 focus:ring-blue-600 focus:ring-1 placeholder-gray-500 border-gray-300 dark:border-gray-500' }}

    dark:bg-gray-600 dark:text-white transition-colors duration-500
     block w-full sm:text-sm rounded-md h-10 border {{ $class }}" {{ $multiple ? 'multiple' : '' }}
            id="{{ $id }}" {{ $attributes }}>

        @foreach($options as $key => $option)
            <option value="{{ $key }}"
                    @if($multiple)
                    {{ in_array($key, $checked) ? 'selected' : '' }}
                    @else
                    {{ (old($attributes->get('name')) ?? $checked) == $key ? 'selected' : '' }}
                    @endif
            >
                {{ $option }}
            </option>
        @endforeach

    </select>

        @if($error)
            @foreach($error as $e)
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $e }}</p>
            @endforeach
        @endif

</div>
