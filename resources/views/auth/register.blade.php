<x-layouts.auth>

    <x-slot name="title">Sign Up to Ticket</x-slot>

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900">
        Create a new account
    </h2>

    <form class="space-y-6" action="{{ url('/register') }}" method="POST">
        @csrf
        @honeypot

        <input type="hidden" name="remember" value="true">
        <div class="space-y-3">

            <x-form.input-2 label="Name"
                            id="name" value="{{ old('name') }}"
                            type="text" required />

            <x-form.input-2 label="Username"
                            id="username" value="{{ old('username') }}"
                            type="text" required />

            <x-form.input-2 label="Email"
                            id="email" value="{{ old('email') }}"
                            type="email" required />

            <x-form.input-2 label="Phone"
                            id="phone" value="{{ old('phone') }}"
                            type="phone" required />

            <x-form.input-2 label="Password"
                            id="password" value="{{ old('password') }}"
                            type="password" required />

            <x-form.input-2 label="Confirm Password"
                            id="password_confirmation" value="{{ old('password_confirmation') }}"
                            type="password" required />

            <x-form.gender-switch name="gender" id="gender" label="Gender" />
        </div>

        <div class="flex items-center justify-between">
            <div class="text-md">
                <a href="{{ url('/login') }}" class="font-medium text-blue-800 hover:text-blue-700">
                    Already have an account? Sign in
                </a>
            </div>
        </div>

        <x-button type="submit" class="mx-auto w-full justify-center">
            <x-slot name="svg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg>
            </x-slot>
            Sign up
        </x-button>

        <x-layouts.errors size="w-full" />
    </form>


</x-layouts.auth>