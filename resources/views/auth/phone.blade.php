<x-layouts.auth>

    <x-slot name="title">Reset Password | Ticket</x-slot>

    @if(session("status"))
        <x-layouts.success size="w-full mb-4">
            {{ session("status") }}
        </x-layouts.success>
    @endif

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900">
        Reset Password
    </h2>

    <form class="space-y-6" action="{{ url('/password/phone') }}" method="POST">
        @csrf
        @honeypot

        <div class="rounded-md shadow-sm space-y-3">
            <x-form.input-2 label="Phone Number"
                            id="phone" value="{{ old('phone') }}"
                            type="phone" required/>
        </div>

        <div class="text-sm">
            <a href="{{ url('/login') }}" class="font-medium text-blue-800 hover:text-blue-700">
                ← Back to sign in page
            </a>
        </div>

        <x-button id="reset-button" type="button" class="mx-auto w-full justify-center shadow-lg">
            <x-slot name="svg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                </svg>
            </x-slot>

            Send Verification Code
        </x-button>

        <x-layouts.errors size="w-full"/>

        @push('scripts')
            <script>
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('reset-button', {
                    'size': 'invisible',
                    'callback': (response) => {
                        // reCAPTCHA solved, allow signInWithPhoneNumber.
                        console.log(response);
                    }
                });
            </script>
        @endpush
    </form>


</x-layouts.auth>