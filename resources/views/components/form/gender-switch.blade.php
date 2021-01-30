@props([
    'id',
    'name',
    'class' => null,
    'label' => null,
    'livewire' => false
])


<div class="flex flex-col">
    @isset($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endisset
    <div class="mt-1" x-data="{ value: @if($livewire) @entangle('gender').defer @else 1 @endif }">
        <div class="{{ $class }} select-none bg-blue-200
         w-28 h-10 rounded-full cursor-pointer flex flex-row justify-center items-center transition-all"
             :class="((!!value) ? 'bg-blue-200' : 'bg-pink-200')" @click="value = ((!value) ? 1 : 0);">

            <div class="font-bold w-full flex flex-row justify-center items-center transition-all"
                 :class="((!!value) ? 'text-blue-800' : 'text-pink-800')">

                    <svg class="h-6 w-6 transform duration-300 transition-all" :class="!value ? 'rotate-135' : ''"
                         fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path d="M9 9c1.29 0 2.5.41 3.47 1.11L17.58 5H13V3h8v8h-2V6.41l-5.11
                    5.09c.7 1 1.11 2.2 1.11 3.5a6 6 0 0 1-6 6a6 6 0 0 1-6-6a6 6 0 0
                    1 6-6m0 2a4 4 0 0 0-4 4a4 4 0 0 0 4 4a4 4 0 0 0 4-4a4 4 0 0 0-4-4z"/>
                        <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)"/>
                    </svg>

                    <span x-text="((!!value) ? 'Male' : 'Female')"></span>
            </div>

        </div>

        <input {{ $attributes }} type="number" :value="value"
               name="{{ $name }}" id="{{ $id }}"
               class="sr-only" />
    </div>

</div>