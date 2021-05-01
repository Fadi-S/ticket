@props([
    'label' => null,
    'id' => rand(),
    'checked' => false,
])

<div class="flex flex-col space-y-4">
    <div class="ml-3 text-sm">
        <label for="{{ $id }}" class="text-gray-700 dark:text-white rtl:text-right">{{ $label }}</label>
    </div>

    <div class="flex items-start">
        <div class="h-5 flex items-center">
            <input id="{{ $id }}" {{ $attributes }} {{ $checked ? 'checked' : '' }}
            type="checkbox" class="focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300 rounded">
        </div>
    </div>
</div>
