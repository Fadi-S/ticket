@props([
    'class' => null,
    'size' => 'sm:m-4 lg:m-8',
    'show' => true,
    ])

@if($show)
<div class="bg-white dark:bg-gray-700 transition-colors duration-500
 my-4 {{ $size }} sm:rounded-lg p-4 sm:p-8 {{ $class }}" {{ $attributes }}>
        {{ $slot }}
</div>
@else
        {{ $slot }}
@endif
