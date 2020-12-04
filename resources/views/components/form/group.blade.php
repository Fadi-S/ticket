@props(['class' => null])

<div class="{{ $class ?? 'md:space-x-2 md:space-y-0 space-y-6' }} flex flex-col md:flex-row" {{ $attributes }}>
    {{ $slot }}
</div>