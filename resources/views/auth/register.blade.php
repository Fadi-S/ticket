<x-layouts.auth>

    <x-slot name="title">Sign In to Ticket</x-slot>

    <div>
        <img class="mx-auto h-12 w-auto" src="{{ url("/images/logo.png") }}" alt="Workflow">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Sign in to your account
        </h2>
    </div>
    <form class="mt-8 space-y-6" action="{{ url('/register') }}" method="POST">
        @csrf

        <input type="hidden" name="remember" value="true">
        <div class="rounded-md shadow-sm space-y-4">
            <div>
                <label for="name" class="sr-only">Name</label>
                <input id="name" name="name" type="text"
                       required class="appearance-none rounded-lg
                            relative block w-full px-3 py-2 border
                             border-gray-300 placeholder-gray-500
                              text-gray-900 focus:outline-none
                               focus:ring-blue-500 focus:border-blue-500
                                focus:z-10 sm:text-sm"
                       placeholder="Name" value="{{ old('name') }}">
            </div>

            <div>
                <label for="username" class="sr-only">Username</label>
                <input id="username" name="username" type="text"
                       required class="appearance-none rounded-lg
                            relative block w-full px-3 py-2 border
                             border-gray-300 placeholder-gray-500
                              text-gray-900 focus:outline-none
                               focus:ring-blue-500 focus:border-blue-500
                                focus:z-10 sm:text-sm"
                       placeholder="Username" value="{{ old('username') }}">
            </div>

            <div>
                <label for="email-address" class="sr-only">Email address</label>
                <input id="email-address" name="email" type="email"
                       required class="appearance-none rounded-lg
                            relative block w-full px-3 py-2 border
                             border-gray-300 placeholder-gray-500
                              text-gray-900 focus:outline-none
                               focus:ring-blue-500 focus:border-blue-500
                                focus:z-10 sm:text-sm"
                       placeholder="Email address" value="{{ old('email') }}">
            </div>
            <div>
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password"
                       type="password" required
                       class="appearance-none rounded-lg
                           relative block w-full px-3 py-2 border
                            border-gray-300 placeholder-gray-500
                             text-gray-900 focus:outline-none
                              focus:ring-blue-500 focus:border-blue-500
                               focus:z-10 sm:text-sm"
                       placeholder="Password">
            </div>

            <div>
                <label for="password_confirmation" class="sr-only">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation"
                       type="password" required
                       class="appearance-none rounded-lg
                           relative block w-full px-3 py-2 border
                            border-gray-300 placeholder-gray-500
                             text-gray-900 focus:outline-none
                              focus:ring-blue-500 focus:border-blue-500
                               focus:z-10 sm:text-sm"
                       placeholder="Confirm Password">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="text-md">
                <a href="{{ url('/login') }}" class="font-medium text-blue-800 hover:text-blue-700">
                    Already have an account? Sign in
                </a>
            </div>
        </div>

        <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8
                  11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0
                   10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102
                   0v-1h1a1 1 0 100-2h-1V7z"></path>
              </svg>
          </span>
                Sign up
            </button>
        </div>

        <x-layouts.errors/>
    </form>


</x-layouts.auth>