@props(['class' => ''])

<div class="flex space-x-2 {{ $class }}" {{ $attributes }}>
    {{ $slot }}
</div>