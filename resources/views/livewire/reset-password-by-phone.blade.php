<div>
    <x-slot name="title">Verify Phone Number | Ticket</x-slot>

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

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900">
        @if($state == 0)
            Send code to phone
        @elseif($state == 1)
            Verification Code sent to {{ session('phone') }}
        @elseif($state == 2)
            Reset Password
        @endif
    </h2>

    <div class="mb-6">
        @if($state == 0)
            <form class="space-y-6" action="#" method="POST" wire:submit.prevent="sendCode">
                @csrf

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Phone Number
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                  +2
                </span>
                        <input type="text" wire:model="phone" name="phone" id="phone" required value="{{ old('phone') }}"
                               class="flex-1 min-w-0 block w-full px-3 py-2 border rounded-none rounded-r-md
                        focus:outline-none focus:border-blue-500 p-2 focus:border-3 block sm:text-sm border-gray-300 h-10"
                               placeholder="01000000000">
                    </div>
                </div>

                <div wire:ignore>
                    <button class="sr-only" id="recaptcha-div"></button>
                </div>


                <x-button :disabled="is_null($reCaptcha)" type="submit" class="mx-auto w-full justify-center shadow-lg">
                    <x-slot name="svg">
                        <svg wire:loading.remove wire:target="sendCode" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>

                        <x-svg.spinner wire:loading wire:target="sendCode" />
                    </x-slot>

                    Send Code
                </x-button>
            </form>
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
        @endif

        @if($state == 1)
            <form class="space-y-6" action="#" method="POST" wire:submit.prevent="confirmCode">
                @csrf
                <x-form.input-2 label="Code" wire:model="code" autocomplete="off"
                                placeholder="Verification Code"
                                id="code" type="text" required />

                <x-button id="reset-button" type="submit"
                          class="mx-auto w-full justify-center shadow-lg">
                    <x-slot name="svg">
                        <svg wire:loading.remove wire:target="confirmCode" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"></path>
                        </svg>

                        <x-svg.spinner wire:loading wire:target="confirmCode" />
                    </x-slot>

                    Confirm Code
                </x-button>
            </form>
        @endif

        @if($state == 2)
            <form class="space-y-6" action="#" method="POST" wire:submit.prevent="resetPassword">
                @csrf
                <x-form.input-2 label="New Password" wire:model="password" placeholder="New Password"
                                id="password" type="password" required />

                <x-form.input-2 label="Confirm New Password" wire:model="password_confirmation"
                                placeholder="Confirm New Password"
                                id="password_confirmation" type="password" required />

                <x-button id="reset-button" type="submit"
                          class="mx-auto w-full justify-center shadow-lg">
                    <x-slot name="svg">
                        <svg wire:loading.remove wire:target="resetPassword" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"></path>
                        </svg>

                        <x-svg.spinner wire:loading wire:target="resetPassword" />
                    </x-slot>

                    Change Password
                </x-button>
            </form>
        @endif
    </div>


    <x-layouts.errors size="w-full"/>
</div>
