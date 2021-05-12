<div>
    <script src="{{ mix('/js/firebase.js') }}"></script>

    @push('actions')
        <div>
            <form action="{{ url('/logout') }}" method="POST" class="flex justify-end mx-2 my-2">
                @csrf

                <button class="border-2 border-red-600 flex focus:bg-red-700
                 focus:outline-none hover:bg-red-600 hover:text-white focus:text-white
                  px-4 py-2 rounded-md text-red-600 transition-dark"
                        type="submit">
                    <div>
                        <x-svg.logout />
                    </div>

                    {{ __('Sign Out') }}
                </button>
            </form>
        </div>
    @endpush

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900 dark:text-gray-100">
        {{ __('Verify Phone Number') }}
    </h2>

    <div class="flex flex-col space-y-4 items-start">
        <span class="dark:text-gray-400 mx-auto my-2 text-gray-700 text-md">
            {{ __('To make sure that this is your phone number, we will send a verification code') }}
        </span>

        <div class="w-full">
            @if(session("status"))
                <x-layouts.success size="w-full mb-4">
                    {{ session("status") }}
                </x-layouts.success>
            @endif

            @if(session("error"))
                <x-layouts.error size="w-full mb-4">
                    {{ session("error") }}
                </x-layouts.error>
            @endif
        </div>

        <div class="w-full">
            @if($edit)
                <livewire:users.user-form :card="false" :user="auth()->user()" :only="['user.phone']" />
            @else
                <div class="flex items-center justify-start">
                <span class="font-bold">
                    @if($sent)
                        {{ __('A verification code was sent to :phone, please check your phone\'s sms', ['phone' => auth()->user()->phone]) }}
                    @else
                        {{ __('A verification code will be sent to :phone', ['phone' => auth()->user()->phone]) }}
                    @endif
                </span>
                    <button class="focus:outline-none font-bold
                 mx-1 text-gray-400 text-sm text-underline"
                            wire:click="$set('edit', true)"
                            type="button">
                        {{ __('Edit') }}
                    </button>
                </div>
            @endif
        </div>

        <div>
            @unless($edit)
                <x-button type="button" wire:click="send"
                          class="mx-auto w-full justify-center shadow-lg">
                    <x-slot name="svg">
                        <svg wire:loading.remove wire:target="send" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>

                        <x-svg.spinner wire:loading wire:target="send" />
                    </x-slot>

                    @if($sent)
                        {{ __('Resend Code') }}
                    @else
                        {{ __('Send Code') }}
                    @endif
                </x-button>
            @endunless
        </div>

        <div wire:ignore>
            <button type="button" class="sr-only" id="recaptcha-div"></button>
        </div>

        @push('scripts')
            <script>
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-div', {
                    'size': 'invisible',
                    'callback': recaptcha => @this.set('reCaptcha', recaptcha),
                });

                window.recaptchaVerifier.render().then(
                    () => document.querySelector('#recaptcha-div').dispatchEvent(new Event('click'))
                );
            </script>
        @endpush
        <div class="w-full">
            @if($sent)
                <form wire:submit.prevent="verify" class="space-y-4">
                    @csrf
                    <x-form.input-2 label="{{ __('Verification Code') }}" wire:model.defer="code"
                                    autocomplete="off"
                                    placeholder="{{ __('Verification Code') }}"
                                    id="code" type="text" required />

                    <x-button id="reset-button" type="submit"
                              class="mx-auto w-full justify-center shadow-lg">
                        <x-slot name="svg">
                            <svg wire:loading.remove wire:target="verify" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>

                            <x-svg.spinner wire:loading wire:target="verify" />
                        </x-slot>

                        {{ __('Confirm Code') }}
                    </x-button>
                </form>
            @endif
        </div>
    </div>
</div>
