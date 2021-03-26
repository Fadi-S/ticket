<div x-data="{ message: '', level: '', important: false, show: false }"
     @message.window="
     if($event.detail != null) {
     show=true;
     message=$event.detail?.message;
     level=$event.detail?.level;
     important=$event.detail?.important;
     setTimeout(function() { if(!important)show=false; }, 5000);}">

    <div x-show="show" style="display: none;"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed z-50 inset-0 flex items-end justify-center px-4 py-6
         pointer-events-none sm:p-6 sm:items-start sm:justify-end">
        <div class="max-w-sm w-full bg-white dark:bg-gray-600 shadow-lg rounded-lg
         pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p x-text="message" class="text-sm dark:text-gray-200 font-medium text-gray-900"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex" x-show="important">
                        <button @click="show=false" class="bg-white dark:bg-gray-600 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">{{ __('Close') }}</span>
                            <x-svg.x />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>