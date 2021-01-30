<x-layouts.auth>

    <x-slot name="title">Sign In to Ticket</x-slot>

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900">
        Sign in to your account
    </h2>

    <form class="space-y-6" action="{{ url('/login') }}" method="POST">
        @csrf
        @honeypot

        <input type="hidden" name="remember" value="true">
        <div class="rounded-md shadow-sm space-y-3">
            <x-form.input-2 label="Email, Phone or Username"
                            id="email" value="{{ old('email') }}"
                            type="text" required />

            <x-form.input-2 label="Password"
                            id="password" value="{{ old('password') }}"
                            type="password" required />
        </div>

        <div class="flex items-center justify-between">
            <div class="text-sm w-10/12">
                <a href="{{ url('/register') }}" class="font-medium text-blue-800 hover:text-blue-700">
                    Sign Up for new account
                </a>
            </div>


            <div class="flex justify-end text-sm w-10/12">
                <a href="{{ url('password/forgot') }}" class="font-medium text-blue-800 hover:text-blue-700">
                    Forgot your password?
                </a>
            </div>
        </div>

        <x-button type="submit" class="mx-auto w-full justify-center shadow-lg">
            <x-slot name="svg">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </x-slot>

            Sign in
        </x-button>

        <x-layouts.errors size="w-full" />
    </form>


</x-layouts.auth>