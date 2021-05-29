@props([
    'size' => 'w-6 h-6',
    'active',
])
<div class="flex">
    <div class="rounded-full p-1 text-center
                            {{ $active ? 'bg-green-200' : 'bg-red-200' }}">
        @if($active)
            <x-svg.check class="text-green-800" />
        @else
            <x-svg.x size="w-5 h-5" class="text-red-800" />
        @endif
    </div>
</div>

