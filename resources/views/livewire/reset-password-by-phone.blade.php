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
            Verification Code sent to {{ session('phone') }}
        @elseif($state == 1)
            Reset Password
        @endif
    </h2>

    <div class="space-y-6">
        @if($state == 0)
            <x-form.input-2 label="Code" wire:model="code" placeholder="Verification Code"
                            id="code" type="text" required />

            <x-button id="reset-button" type="button" wire:click="confirmCode"
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
        @endif

        @if($state == 1)
            <x-form.input-2 label="New Password" wire:model="password" placeholder="New Password"
                            id="password" type="password" required />

            <x-form.input-2 label="Confirm New Password" wire:model="password_confirmation"
                            placeholder="Confirm New Password"
                            id="password_confirmation" type="password" required />

            <x-button id="reset-button" type="button" wire:click="resetPassword"
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
        @endif
    </div>


    <x-layouts.errors size="w-full"/>
</div>
