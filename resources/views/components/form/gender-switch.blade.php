@props([
    'id',
    'name',
    'class' => null,
    'label' => null,
    'livewire' => false
])


<div class="flex flex-col">
    <div class="mt-1" x-data="{ value: @if($livewire) @entangle('gender').defer @else 1 @endif }">
        <div class="{{ $class }} select-none bg-blue-200
         w-28 h-10 rounded-full cursor-pointer flex flex-row justify-center
         duration-500
         items-center transition-all"
             :class="((!!value) ? 'bg-blue-200 dark:bg-indigo-600' : 'bg-pink-200 dark:bg-pink-500')" @click="value = ((!value) ? 1 : 0);">

            <div class="font-bold w-full flex flex-row justify-center items-center transition-all"
                 :class="((!!value) ? 'text-blue-800 dark:text-blue-200' : 'text-pink-800 dark:text-pink-200')">

                <x-svg.gender x-bind:class="!value ? 'rotate-135' : ''" />

                <span x-text="((!!value) ? '{{ __('Male') }}' : '{{ __('Female') }}')"></span>
            </div>

        </div>

        <input {{ $attributes }} type="number" :value="value"
               name="{{ $name }}" id="{{ $id }}"
               class="sr-only" />
    </div>

</div>
