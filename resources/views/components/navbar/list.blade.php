<button class="flex items-center py-2 px-4 bg-opacity-25 text-gray-100 w-full focus:outline-none"
        type="button"
        x-data="{isOpen: false}" @click="isOpen = !isOpen">

    <div class="flex-col w-full">
        <div class="justify-between flex items-center w-full hover:text-gray-400">
            <div class="flex items-center">

                {{ $svg }}

                <span class="mx-3 font-semibold">{{ $label }}</span>
            </div>

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 :class="isOpen ? 'transition duration-300 transform rotate-90' : 'transition duration-300 transform '"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>

        <div x-show="isOpen" style="display: none;"
             x-transition:enter="transition transform duration-200"
             x-transition:enter-start="scale-50 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition transform duration-100"
             x-transition:leave-end="scale-50 opacity-0"
             class="bg-white rounded-lg mt-4 shadow-lg px-2 py-2 space-y-2 w-full">
            {{ $slot }}
        </div>
    </div>
</button>