@props([
    'force' => false,
    'defaultState' => 'false',
])

<div x-data="{ open: {{ $defaultState }}, details:{} }" x-init="
  () => document.body.classList.add('overflow-hidden');
  $watch('open', value => {
    if (value === true) { document.body.classList.add('overflow-hidden') }
    else { document.body.classList.remove('overflow-hidden') }
  });" x-show="open" class="fixed z-10 inset-0 overflow-y-auto" {{ $attributes }}>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div x-show="open" x-description="Background overlay, show/hide based on modal state."
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div {{ !$force ? '@click.away=open=false' : '' }} x-show="open" x-description="Modal panel, show/hide based on modal state."
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"

             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl
                      transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             role="dialog" aria-modal="true" aria-labelledby="modal-headline">

            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">

                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        {{ $svg }}
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        {{ $body }}
                    </div>

                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
               {{ $footer }}
            </div>
        </div>
    </div>
</div>