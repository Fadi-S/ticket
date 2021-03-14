<x-layouts.auth>

    <x-slot name="title">Reset Password | Ticket</x-slot>

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900 dark:text-gray-100">
        {{ __('Reset your password') }}
    </h2>

    <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
        @csrf
        @honeypot

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="rounded-md shadow-sm space-y-3">
            <x-form.input-2 label="{{ __('Email') }}"
                            id="email" value="{{ old('email') }}"
                            type="email" required/>

            <x-form.input-2 label="{{ __('Password') }}"
                            id="password"
                            type="password" required/>

            <x-form.input-2 label="{{ __('Confirm Password') }}"
                            id="password_confirmation"
                            type="password" required/>
        </div>

        <x-button type="submit" class="mx-auto w-full justify-center shadow-lg">
            <x-slot name="svg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                    <path fill-rule="evenodd"
                          d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                          clip-rule="evenodd"></path>
                </svg>
            </x-slot>

            {{ __('Reset Password') }}
        </x-button>

        <x-layouts.errors size="w-full"/>
    </form>


</x-layouts.auth>