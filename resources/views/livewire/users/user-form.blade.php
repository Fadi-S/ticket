<div>
    <x-card>
        <form method="POST" action="{{ url('users') }}" wire:submit.prevent="save">
            @csrf

            <div class="space-y-6">
                <x-form.group>
                    <x-form.input type="text" size="w-1/2" name="name" id="name" label="Name" wire:model="user.name"
                                  :value="!$create ? $user->name : null" placeholder="Name"/>

                    <x-form.input type="text" size="w-1/2" name="username" id="username" label="Username"
                                  wire:model="user.username"
                                  :value="!$create ? $user->username : null" placeholder="Username"/>
                    <small id="message"></small>
                </x-form.group>

                <x-form.group>
                    <x-form.input wire:model="user.email" type="email" size="w-1/2" name="email" id="email"
                                  label="Email"
                                  :value="!$create ? $user->email : null" placeholder="Email"/>

                    <x-form.input wire:model="password" type="password" size="w-1/2" name="password" id="password"
                                  label="Password" placeholder="Password"/>
                </x-form.group>

                <x-button type="submit" class="mx-auto mt-2">
                    <x-slot name="svg">

                        <svg wire:loading wire:target="save" class="animate-spin h-6 w-6 text-white"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <svg wire:loading.remove wire:target="save" class="w-6 h-6" fill="currentColor"
                             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </x-slot>
                    Save User
                </x-button>

                @if(session()->has('success'))
                    <div class="bg-white p-4 border w-full sm:w-9/12 md:w-1/2 lg:w-1/4 mx-auto
                     rounded-lg shadow-md flex space-x-4 items-center justify-center">
                        <svg class="w-6 h-6 text-green-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000
                                  16zm3.707-9.293a1 1 0 00-1.414-1.414L9
                                   10.586 7.707 9.293a1 1 0 00-1.414 1.414l2
                                    2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"></path>
                        </svg>

                        <span class="text-gray-700 text-md font-medium">{{ session()->get('success') }}</span>
                    </div>
                @endif

                @include("errors.list")
            </div>

        </form>
    </x-card>

</div>
