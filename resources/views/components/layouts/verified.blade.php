@props(['class' => null, 'size' => 'w-4 h-4'])

<div class="text-xs bg-blue-600 text-white rounded-full {{ $class }}">
    <x-svg.check :size="$size" />
</div>
