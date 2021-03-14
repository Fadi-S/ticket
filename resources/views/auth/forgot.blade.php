<x-layouts.auth>

    <x-slot name="title">Reset Your Password</x-slot>

    <div x-data="{ selected: 0 }">
        <fieldset>
            <legend id="radiogroup-label" class="sr-only">
                {{ __('Reset Password Using...') }}
            </legend>
            <ul class="space-y-4" role="radiogroup" aria-labelledby="radiogroup-label">
                <li @click="selected=0" id="radiogroup-option-0" tabindex="0" role="radio" aria-checked="true"
                    class="group relative bg-white dark:bg-gray-800 rounded-lg
                     shadow-sm cursor-pointer focus:outline-none focus:ring-1
                      focus:ring-offset-2 focus:ring-indigo-500">
                    <div class="rounded-lg border border-gray-300 bg-white
                     dark:bg-gray-800 px-6 py-4 hover:border-gray-400
                      sm:flex sm:justify-between">
                        <div class="flex items-center">
                            <div class="text-sm">
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Reset Account By Phone Number') }}
                                </p>
                                <div class="text-gray-500 dark:text-gray-400">
                                    <p class="sm:inline">{{ __('You will receive a verification code to your phone number') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 flex text-sm sm:mt-0 sm:block sm:ml-4 sm:text-right">
                            <div class="font-medium text-gray-900">
                                <svg class="w-6 h-6 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div :class="(selected==0) ? 'border-indigo-500' : 'border-transparent'"
                         class="absolute inset-0 rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                </li>

                <li @click="selected=1" id="radiogroup-option-1" tabindex="-1" role="radio" aria-checked="false"
                    class="group relative bg-white dark:bg-gray-800 rounded-lg shadow-sm cursor-pointer focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-indigo-500">
                    <div class="rounded-lg border border-gray-300 bg-white dark:bg-gray-800 px-6 py-4 hover:border-gray-400 sm:flex sm:justify-between">
                        <div class="flex items-center">
                            <div class="text-sm">
                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Reset Account By Email') }}
                                </p>
                                <div class="text-gray-500 dark:text-gray-400">
                                    <p class="sm:inline">{{ __('You will receive a verification to your email') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 flex text-sm sm:mt-0 sm:block sm:ml-4 sm:text-right">
                            <div class="font-medium text-gray-900">
                                <svg class="w-6 h-6 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div :class="(selected==1) ? 'border-indigo-500' : 'border-transparent'"
                         class="absolute inset-0 rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                </li>
            </ul>
        </fieldset>

        <x-button type="button" @click="window.location.href=((selected == 0) ? '{{ url('password/phone') }}' : '{{ url('password/reset') }}')" class="mt-4 mx-auto w-full justify-center shadow-lg">
            <x-slot name="svg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </x-slot>

            {{ __('Continue') }}
        </x-button>
    </div>

</x-layouts.auth>