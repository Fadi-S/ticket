<x-card>
    <x-slot name="title">User Form | Ticket</x-slot>

    <form method="POST" action="{{ url('users') }}" wire:submit.prevent="save">
        @csrf

        <div class="space-y-6">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                <x-form.input wire:model.lazy="user.name" required type="text"
                              size="w-full" name="name" id="name"
                              label="Name *"  placeholder="Name" />

                <x-form.input wire:model.lazy="user.arabic_name"
                              required type="text"
                              size="w-full" name="arabic_name"
                              id="arabic_name"
                              dir="rtl"
                              label="Name in arabic *"  placeholder="الأسم بالعربية" />

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

                <x-form.input wire:model.lazy="user.national_id" type="text"
                              size="w-full" name="national_id" id="national_id"
                              label="National ID" placeholder="National ID" />

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
