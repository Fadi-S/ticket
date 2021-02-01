<x-card>
    <x-slot name="title">User Form | Ticket</x-slot>

    <div class="rounded-md bg-blue-50 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <!-- Heroicon name: information-circle -->
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1 md:flex md:justify-between">
                <p class="text-sm text-blue-700">
                    You have to fill phone number or email
                </p>
            </div>
        </div>
    </div>


    <form method="POST" action="{{ url('users') }}" wire:submit.prevent="save">
        @csrf

        <div class="space-y-6">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                <x-form.input wire:model.lazy="user.name" required type="text"
                              size="w-full" name="name" id="name"
                              label="Name *"  placeholder="Name" />

                @if(auth()->user()->isAdmin())
                    <x-form.input wire:model.lazy="user.username" required type="text"
                                  size="w-full" name="username" id="username"
                                  label="Username *" placeholder="Username" />
                @endif

                <x-form.input wire:model.lazy="user.email" type="email"
                              size="w-full" name="email" id="email"
                              label="Email" placeholder="Email" />

                <x-form.input wire:model.lazy="user.phone" type="phone"
                              size="w-full" name="phone" id="phone"
                              label="Phone Number" placeholder="Phone Number" />

                @if(auth()->user()->isAdmin() && ($isCreate || !$user->isSignedIn()))
                    <x-form.input wire:model.lazy="password" type="password"
                                  size="w-full" name="password" id="password"
                                  label="Password" placeholder="Password" />
                @endif

                @if(auth()->user()->isAdmin() && !$user->isSignedIn())
                    <x-form.select wire:model="role_id" name="role_id"
                                   id="role_id" size="w-full"
                                   label="Role" :options="$roles" />
                @endif

                <x-form.gender-switch :livewire="true" name="gender" id="gender" label="Gender" />
            </div>

            <x-button type="submit" class="mx-auto mt-2">
                <x-slot name="svg">
                    <x-svg.spinner wire:loading wire:target="save" />

                    <x-svg.edit wire:loading.remove wire:target="save" />
                </x-slot>

                Save
            </x-button>

            @if(session()->has('success'))
                <x-layouts.success>
                    {{ session('success') }}
                </x-layouts.success>
            @endif

            <x-layouts.errors />
        </div>

    </form>

    <script>
        window.addEventListener('closeTab', () => window.close());
    </script>

</x-card>
