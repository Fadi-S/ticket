
<x-slot name="title">
    @if($card)
        {{ $isCreate ? 'Add User' : 'Edit ' . $user->first_name }} | {{ config('app.name') }}
    @endif
</x-slot>

<div>
    <x-card :show="$card">

        @if(!$isCreate && auth()->user()->can('users.forceDelete') && !$user->isSignedIn())
            <div class="mb-4">
                <button type="button"
                        class="flex items-center border border-red-500 rounded-md px-4 py-2 hover:bg-red-600 transition-dark mb-4 focus:outline-none"
                        x-data="{  }" @click="$dispatch('open')">
                    <div class="ltr:mr-2 rtl:ml-2">
                        <x-svg.trash />
                    </div>
                    {{ __('Delete User') }}
                </button>
            </div>
        @endif

        <form wire:submit.prevent="save">
            @csrf

            <div class="space-y-6">
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">

                    @unless(config('settings.arabic_name_only'))
                        @if($this->showField('user.name'))
                            <x-form.input wire:model.lazy="user.name" required type="text"
                                          :error="$errors->get('user.name')" autocomplete="off"
                                          size="col-span-1" name="name" id="name" dir="ltr"
                                          label="{{ __('Name in english') }} *"  placeholder="{{ __('Name in english') }}" />
                        @endif
                    @endunless

                    @if($this->showField('user.arabic_name'))
                        <x-form.input wire:model.lazy="user.arabic_name"
                                      required type="text" autocomplete="off"
                                      :error="$errors->get('user.arabic_name')"
                                      size="col-span-1" name="arabic_name"
                                      id="arabic_name"
                                      dir="rtl"
                                      label="{{ __('Name in arabic') }} *"  placeholder="{{ __('Name in arabic') }}" />
                    @endif


                        @if($this->showField('user.phone'))
                            <x-form.input wire:model.lazy="user.phone" type="text" dir="ltr"
                                          size="col-span-1" name="phone" id="phone"
                                          :error="$errors->get('user.phone')" autocomplete="off"
                                          label="{{ __('Phone') }} *" placeholder="{{ __('Phone') }}" />
                        @endif

                        @if(config('settings.ask_for_email'))
                            @if($this->showField('user.email'))
                                <x-form.input wire:model.lazy="user.email" type="email"
                                              size="col-span-1" name="email" id="email" autocomplete="off"
                                              :error="$errors->get('user.email')" dir="ltr"
                                              label="{{ __('Email') }} ({{ __('Optional') }})" placeholder="{{ __('Email') }}" />
                            @endif
                        @endif

                    @if($this->showField('user.national_id'))
                    <x-form.input wire:model.lazy="user.national_id" type="text"
                                  :error="$errors->get('user.national_id')" autocomplete="off"
                                  size="col-span-1" name="national_id" id="national_id"
                                  label="{{ __('National ID') }}" placeholder="{{ __('National ID') }}" />
                    @endif

                    @if($this->showField('password') && auth()->user()->can('users.edit') && ($isCreate || !$user->isSignedIn()))
                        <x-form.input wire:model.lazy="password" type="password"
                                      size="col-span-1" name="password" id="password"
                                      :error="$errors->get('password')" autocomplete="off"
                                      label="{{ __('Password') }}" placeholder="{{ __('Password') }}" />
                    @endif

                    @if($this->showField('role_id') && auth()->user()->can('editRole') && !$user->isSignedIn())
                        <x-form.select wire:model="role_id" name="role_id"
                                       id="role_id" size="col-span-1" dir="ltr"
                                       label="{{ __('Role') }}" :options="$roles" />
                    @endif

                  @if($this->showField('user.location_id') && (($isCreate || !$user->location_id) || auth()->user()->can('users.edit')))
                        <x-form.select label="{{ __('Location of stay') }}"
                                       dir="rtl"
                                       :error="$errors->get('user.location_id')"
                                       wire:model="user.location_id"
                                       id="location_id"
                                       :options="$locations" />
                        @endif

                    @if($this->showField('gender'))
                        <x-form.gender-switch :livewire="true" wire:model="gender" name="gender" id="gender" label="{{ __('Gender') }}" />
                    @endif
                </div>

                <x-button type="submit" class="mt-2 w-full
                sm:max-w-sm mx-auto
                flex items-center justify-center text-center">
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

            <x-layouts.modal id="user-delete-modal" size="w-full rounded-none sm:rounded-lg md:max-w-2xl
             lg:max-w-4xl my-2 sm:max-w-xl" @open.window="open=true" @close.window="open=false">
                <x-slot name="svg">
                    <x-svg.trash color="text-red-600" />
                </x-slot>

                <x-slot name="body">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 flex items-center sm:justify-start sm:mr-3 justify-center" id="modal-headline">
                        {{ __('Deleting User') }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-300">
                            {{ __('Are you sure you want to delete that user?') }}
                        </p>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <div class="space-x-2 flex justify-between">

                        <x-button class="mx-2" type="button" @click="open = false;"
                                  color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                   bg:text-gray-900 text-gray-700 dark:text-gray-200
                                   hover:bg-gray-50 border border-gray-400">
                            {{ __("Cancel") }}
                        </x-button>

                        <x-button type="button" wire:click="delete"
                                  color="bg-red-500 hover:bg-red-600 dark:bg-red-700
                                   dark:hover:bg-red-500 text-white">
                            <x-slot name="svg">
                                <x-svg.trash wire:loading.remove wire:target="delete" />
                                <x-svg.spinner wire:loading wire:target="delete" />
                            </x-slot>
                            {{ __('Delete User') }}
                        </x-button>
                    </div>
                </x-slot>
            </x-layouts.modal>

        <script>
            window.addEventListener('closeTab', () => window.close());
        </script>
        </x-card>
</div>
