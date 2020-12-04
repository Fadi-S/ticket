@props([
    'color' => 'bg-gray-600',
    'class' => null
])

<div class="w-full h-full col-span-4 sm:col-span-2 xl:col-span-1 {{ $class }}" {{ $attributes }}>
    <div class="flex h-full items-center px-5 py-6 shadow-sm rounded-md bg-white">
        <div class="p-3 rounded-full {{ $color }} bg-opacity-75">

            {{ $svg }}

        </div>

        <div class="mx-5 w-full">
            {{ $slot }}
        </div>
    </div>
</div>