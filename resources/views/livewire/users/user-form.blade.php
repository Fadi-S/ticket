<div>
    <x-card :show="$card">
        <x-slot name="title">User Form | Ticket</x-slot>

        @if(!$isCreate && auth()->user()->can('users.forceDelete') && !$user->isSignedIn())
            <div class="mb-4">
                <x-button type="button" wire:click="delete"
                        color="bg-red-500 hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-500">
                    <x-slot name="svg">
                        <x-svg.trash wire:loading.remove wire:target="delete" />
                        <x-svg.spinner wire:loading wire:target="delete" />
                    </x-slot>
                    {{ __('Delete User') }}
                </x-button>
            </div>
        @endif

        <form wire:submit.prevent="save">
            @csrf

            <div class="space-y-6">
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">

                    @if($this->showField('user.name'))
                        <x-form.input wire:model.lazy="user.name" required type="text"
                                      :error="$errors->get('user.name')"
                                      size="col-span-1" name="name" id="name" dir="ltr"
                                      label="{{ __('Name in english') }} *"  placeholder="{{ __('Name in english') }}" />
                    @endif

                    @if($this->showField('user.arabic_name'))
                        <x-form.input wire:model.lazy="user.arabic_name"
                                      required type="text"
                                      :error="$errors->get('user.arabic_name')"
                                      size="col-span-1" name="arabic_name"
                                      id="arabic_name"
                                      dir="rtl"
                                      label="{{ __('Name in arabic') }} *"  placeholder="{{ __('Name in arabic') }}" />
                    @endif

                    @if($this->showField('tempUsername') && auth()->user()->isAdmin())
                        <x-form.input wire:model.lazy="tempUsername" required type="text"
                                      :error="$errors->get('tempUsername')" dir="ltr"
                                      size="col-span-1" name="username" id="username"
                                      label="{{ __('Username') }} *" placeholder="{{ __('Username') }}" />
                    @endif

                    @if($this->showField('user.phone'))
                        <x-form.input wire:model.lazy="user.phone" type="text" dir="ltr"
                                      size="col-span-1" name="phone" id="phone"
                                      :error="$errors->get('user.phone')"
                                      label="{{ __('Phone') }} *" placeholder="{{ __('Phone') }}" />
                    @endif

                    @if($this->showField('user.email'))
                        <x-form.input wire:model.lazy="user.email" type="email"
                                      size="col-span-1" name="email" id="email"
                                      :error="$errors->get('user.email')" dir="ltr"
                                      label="{{ __('Email') }} ({{ __('Optional') }})" placeholder="{{ __('Email') }}" />
                    @endif

                    @if($this->showField('user.national_id'))
                    <x-form.input wire:model.lazy="user.national_id" type="text"
                                  :error="$errors->get('user.national_id')"
                                  size="col-span-1" name="national_id" id="national_id"
                                  label="{{ __('National ID') }}" placeholder="{{ __('National ID') }}" />
                    @endif

                    @if($this->showField('password') && auth()->user()->can('users.edit') && ($isCreate || !$user->isSignedIn()))
                        <x-form.input wire:model.lazy="password" type="password"
                                      size="col-span-1" name="password" id="password"
                                      :error="$errors->get('password')"
                                      label="{{ __('Password') }}" placeholder="{{ __('Password') }}" />
                    @endif

                    @if($this->showField('role_id') && auth()->user()->can('editRole') && !$user->isSignedIn())
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
            </div>

        </form>

        <script>
            window.addEventListener('closeTab', () => window.close());
        </script>
        </x-card>
</div>
