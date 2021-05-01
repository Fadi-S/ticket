@props([
    'class' => null,
    'id' => rand(),
    'label' => null,
    'default' => 'false',
])

<div class="{{ $class }} flex flex-col items-center items-start justify-start space-y-2">

    @if($label)
        <label class="dark:text-white rtl:text-right" for="{{ $id }}">{{ $label }}</label>
    @endif

    <button id="{{ $id }}" {{ $attributes }} type="button" class="bg-gray-200 relative inline-flex flex-shrink-0 h-6
     w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors
      ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2
       focus:ring-blue-500" x-data="{ on: {{ $default }} }" role="switch"
            aria-checked="false"
            :aria-checked="on.toString()"
            @click="on = !on"
            x-state:on="Enabled"
            x-state:off="Not Enabled"
            :class="{ 'bg-blue-600': on, 'bg-gray-200 dark:bg-gray-500': !(on) }">
        <span class="sr-only">Use setting</span>

        <span aria-hidden="true"
              class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full
               bg-white shadow transform ring-0 transition ease-in-out duration-200"
              x-state:on="Enabled"
              x-state:off="Not Enabled"
              :class="{ 'ltr:translate-x-5 rtl:-translate-x-5 dark:bg-blue-900': on, 'translate-x-0 dark:bg-gray-800': !(on) }"></span>
    </button>

</div>
