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

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">
                Phone Number
            </label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                  +2
                </span>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                       class="flex-1 min-w-0 block w-full px-3 py-2 border rounded-none rounded-r-md
                        focus:outline-none focus:border-blue-500 p-2 focus:border-3 block sm:text-sm border-gray-300 h-10"
                       placeholder="01000000000">
            </div>
        </div>

        <div class="text-sm">
            <a href="{{ url('/login') }}" class="font-medium text-blue-800 hover:text-blue-700">
                ← Back to sign in page
            </a>
        </div>

        <div x-data="{ loading: false }" id="button-container">
            <x-button id="reset-button" type="button" class="mx-auto w-full justify-center shadow-lg">
                <x-slot name="svg">
                    <svg x-show="!loading" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>

                    <x-svg.spinner x-show="loading" />
                </x-slot>

                Send Verification Code
            </x-button>
        </div>


        <x-layouts.errors size="w-full"/>

        @push('scripts')
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let reCaptcha;

                function onClick() {
                    document.getElementById('button-container').__x.$data.loading = true;

                    $.ajax('{{ url('/password/phone') }}', {
                        'method': 'POST',
                        'data': {reCaptcha: reCaptcha, phone: $('#phone').val()},
                        'success': function (response) {
                            document.getElementById('button-container').__x.$data.loading = false;

                            window.location.href = '{{ url("/password/verify") }}';
                        },
                        'error': function(response) {
                            document.getElementById('button-container').__x.$data.loading = false;

                            console.log(response.responseJSON);
                        },
                    });
                }

                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('reset-button', {
                    'size': 'invisible',
                    'callback': function (response) {
                        reCaptcha = response;

                        onClick();
                        $('#reset-button').click(onClick);
                    }
                });

                window.recaptchaVerifier.render();
            </script>
        @endpush
    </form>


</x-layouts.auth>