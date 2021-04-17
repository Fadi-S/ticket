<div>
    <x-card :show="$card">
        <x-slot name="title">User Form | Ticket</x-slot>

        <form method="POST" action="{{ url('users') }}" wire:submit.prevent="save">
            @csrf

            <div class="space-y-6">
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">

                    @if($this->showField('user.name'))
                        <x-form.input wire:model.lazy="user.name" required type="text"
                                      size="col-span-1" name="name" id="name" dir="ltr"
                                      label="{{ __('Name') }} *"  placeholder="{{ __('Name') }}" />
                    @endif

                    @if($this->showField('user.arabic_name'))
                        <x-form.input wire:model.lazy="user.arabic_name"
                                      required type="text"
                                      size="col-span-1" name="arabic_name"
                                      id="arabic_name"
                                      dir="rtl"
                                      label="{{ __('Name in arabic') }} *"  placeholder="{{ __('Name in arabic') }}" />
                    @endif

                    @if($this->showField('tempUsername') && auth()->user()->isAdmin())
                        <x-form.input wire:model.lazy="tempUsername" required type="text"
                                      size="col-span-1" name="username" id="username"
                                      label="{{ __('Username') }} *" placeholder="{{ __('Username') }}" />
                    @endif

                    @if($this->showField('user.phone'))
                        <x-form.input wire:model.lazy="user.phone" type="text" dir="ltr"
                                      size="col-span-1" name="phone" id="phone" required
                                      label="{{ __('Phone') }} *" placeholder="{{ __('Phone') }}" />
                    @endif

                    @if($this->showField('user.email'))
                        <x-form.input wire:model.lazy="user.email" type="email"
                                      size="col-span-1" name="email" id="email"
                                      label="{{ __('Email') }} ({{ __('Optional') }})" placeholder="{{ __('Email') }}" />
                    @endif

                    {{--                <x-form.input wire:model.lazy="user.national_id" type="text"--}}
                    {{--                              size="col-span-1" name="national_id" id="national_id"--}}
                    {{--                              label="{{ __('National ID') }}" placeholder="{{ __('National ID') }}" />--}}

                    @if($this->showField('password') && auth()->user()->isAdmin() && ($isCreate || !$user->isSignedIn()))
                        <x-form.input wire:model.lazy="password" type="password"
                                      size="col-span-1" name="password" id="password"
                                      label="{{ __('Password') }}" placeholder="{{ __('Password') }}" />
                    @endif

                    @if($this->showField('role_id') && auth()->user()->isAdmin() && !$user->isSignedIn())
                        <x-form.select wire:model="role_id" name="role_id"
                                       id="role_id" size="col-span-1"
                                       label="{{ __('Role') }}" :options="$roles" />
                    @endif

                    @if($this->showField('gender'))
                        <x-form.gender-switch :livewire="true" name="gender" id="gender" label="{{ __('Gender') }}" />
                    @endif
                </div>

                <x-button type="submit" class="ml-auto mt-2">
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
</div>
