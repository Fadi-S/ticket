<x-card>
    <x-slot name="title">User Form | Ticket</x-slot>

    <form method="POST" action="{{ url('users') }}" wire:submit.prevent="save">
        @csrf

        <div class="space-y-6">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                <x-form.input wire:model.lazy="user.name" required type="text"
                              size="w-full" name="name" id="name"
                              label="{{ __('Name') }} *"  placeholder="{{ __('Name') }}" />

                <x-form.input wire:model.lazy="user.arabic_name"
                              required type="text"
                              size="w-full" name="arabic_name"
                              id="arabic_name"
                              dir="rtl"
                              label="{{ __('Name in arabic') }} *"  placeholder="{{ __('Name in arabic') }}" />

                @if(auth()->user()->isAdmin())
                    <x-form.input wire:model.lazy="user.username" required type="text"
                                  size="w-full" name="username" id="username"
                                  label="{{ __('Username') }} *" placeholder="{{ __('Username') }}" />
                @endif

                <x-form.input wire:model.lazy="user.email" type="email"
                              size="w-full" name="email" id="email"
                              label="{{ __('Email') }}" placeholder="{{ __('Email') }}" />

                <x-form.input wire:model.lazy="user.phone" type="text" dir="ltr"
                              size="w-full" name="phone" id="phone"
                              label="{{ __('Phone') }}" placeholder="{{ __('Phone') }}" />

                <x-form.input wire:model.lazy="user.national_id" type="text"
                              size="w-full" name="national_id" id="national_id"
                              label="{{ __('National ID') }}" placeholder="{{ __('National ID') }}" />

                @if(auth()->user()->isAdmin() && ($isCreate || !$user->isSignedIn()))
                    <x-form.input wire:model.lazy="password" type="password"
                                  size="w-full" name="password" id="password"
                                  label="{{ __('Password') }}" placeholder="{{ __('Password') }}" />
                @endif

                @if(auth()->user()->isAdmin() && !$user->isSignedIn())
                    <x-form.select wire:model="role_id" name="role_id"
                                   id="role_id" size="w-full"
                                   label="{{ __('Role') }}" :options="$roles" />
                @endif

                <x-form.gender-switch :livewire="true" name="gender" id="gender" label="{{ __('Gender') }}" />
            </div>

            <x-button type="submit" class="mx-auto mt-2">
                <x-slot name="svg">
                    <x-svg.spinner wire:loading wire:target="save" />

                    <x-svg.edit wire:loading.remove wire:target="save" />
                </x-slot>

                {{ __('Save') }}
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
