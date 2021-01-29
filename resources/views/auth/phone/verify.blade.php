<x-layouts.auth>

    <x-slot name="title">Verify Phone Number | Ticket</x-slot>

    @if(session("status"))
        <x-layouts.success size="w-full mb-4">
            {{ session("status") }}
        </x-layouts.success>
    @endif

    <h2 class="sm:mt-0 mt-2 text-center text-2xl font-bold text-gray-900">
        Reset Password
    </h2>

    <form class="space-y-6" action="{{ url('/password/verify') }}" method="POST">
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


        <x-button id="reset-button" type="button" class="mx-auto w-full justify-center shadow-lg">
            <x-slot name="svg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
            </x-slot>

            Send Verification Code
        </x-button>


        <x-layouts.errors size="w-full"/>
    </form>
</x-layouts.auth>