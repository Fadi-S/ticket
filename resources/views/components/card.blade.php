@props(['class'=>null])

<div class="bg-white m-8 rounded-lg p-10 {{ $class }}" {{ $attributes }}>
        {{ $slot }}
</div>