<div>
    <div>
        @if(!$shown)
            <x-button class="m-6" wire:click="show" type="button">
                <x-slot name="svg">
                    <x-svg.spinner wire:target="show" wire:loading />

                    <x-svg.eye wire:target="show" wire:loading.remove />
                </x-slot>

                {{ __('Show change password form') }}
            </x-button>

        @endif
    </div>


    <div>
        @if($shown)
            <x-card>
                <h2 class="font-bold text-2xl mb-6 text-gray-800 dark:text-gray-200">{{ __('Change Password Form') }}</h2>

                <form method="POST" action="#" wire:submit.prevent="change">
                    @csrf

                    <div class="space-y-4">
                        <div class="grid xl:grid-cols-3 lg:grid-cols-2 grid-cols-1 gap-4">
                            <x-form.input required type="password" label="{{ __('Old Password') }}" name="old_password"
                                          id="old_password" wire:model="old_password"
                                          placeholder="{{ __('Old Password') }}" />

                            <x-form.input required type="password" label="{{ __('New Password') }}" name="new_password"
                                          id="new_password" wire:model="new_password"
                                          placeholder="{{ __('New Password') }}" />

                            <x-form.input required type="password" label="{{ __('Confirm New Password') }}"
                                          id="new_password_confirmation" name="new_password_confirmation"
                                          wire:model="new_password_confirmation"
                                          placeholder="{{ __('Confirm New Password') }}" />
                        </div>


                        <x-button type="submit" class="mx-auto">
                            <x-slot name="svg">

                                <x-svg.spinner wire:target="change" wire:loading />

                                <x-svg.edit wire:target="change" wire:loading.remove />

                            </x-slot>

                            {{ __('Change Password') }}
                        </x-button>

                    </div>

                    <div class="mt-4">
                        <x-layouts.errors />

                        @if(session()->has('success'))
                            <x-layouts.success>
                                {{ session('success') }}
                            </x-layouts.success>
                        @endif
                    </div>

                </form>

            </x-card>
        @endif
    </div>
</div>