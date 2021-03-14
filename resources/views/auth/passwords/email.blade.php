<x-layouts.auth>

    <x-slot name="title">Reset Password | Ticket</x-slot>

    @if(session("status"))
        <x-layouts.success size="w-full mb-4">
            {{ session("status") }}
        </x-layouts.success>
    @endif

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900 dark:text-gray-100">
        Reset Password
    </h2>

    <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
        @csrf
        @honeypot

        <div class="rounded-md shadow-sm space-y-3">
            <x-form.input-2 label="Email"
                            id="email" value="{{ old('email') }}"
                            type="email" required/>
        </div>

        <div class="text-sm">
            <a href="{{ url('/login') }}" class="font-medium
            dark:text-blue-400 dark:hover:text-blue-300
            text-blue-800 hover:text-blue-700">
                ← Back to sign in page
            </a>
        </div>

        <x-button type="submit" class="mx-auto w-full justify-center shadow-lg">
            <x-slot name="svg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
            </x-slot>

            Send Password Reset Link
        </x-button>

        <x-layouts.errors size="w-full"/>
    </form>


</x-layouts.auth>