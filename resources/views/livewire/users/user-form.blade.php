<x-card>
    <x-slot name="title">User Form | Ticket</x-slot>

    <form method="POST" action="{{ url('users') }}" wire:submit.prevent="save">
        @csrf

        <div class="space-y-6">
            <x-form.group>
                <x-form.input wire:model="user.name" type="text"
                              size="md:w-1/2 w-full" name="name" id="name"
                              label="Name"  placeholder="Name" />

                <x-form.input wire:model="user.username" type="text"
                              size="md:w-1/2 w-full" name="username" id="username"
                              label="Username" placeholder="Username" />
            </x-form.group>

            <x-form.group>
                <x-form.input wire:model="user.email" type="email"
                              size="md:w-1/2 w-full" name="email" id="email"
                              label="Email" placeholder="Email" />

                <x-form.input wire:model="password" type="password"
                              size="md:w-1/2 w-full" name="password" id="password"
                              label="Password" placeholder="Password" />
            </x-form.group>

            <x-form.group>
                @if(auth()->user()->isAdmin())
                <x-form.select wire:model="role_id" name="role_id"
                               id="role_id" size="md:w-1/2 w-full"
                               label="Role" :options="$roles" />
                @endif

                <x-form.gender-switch :livewire="true" name="gender" id="gender" label="Gender" />
            </x-form.group>


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
