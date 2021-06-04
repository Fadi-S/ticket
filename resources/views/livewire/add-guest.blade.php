<div>
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                <x-form.input wire:model.lazy="user.arabic_name"
                              required type="text"
                              :error="$errors->get('user.arabic_name')"
                              size="col-span-1" name="guest-arabic_name"
                              id="guest-arabic_name"
                              dir="rtl"
                              label="{{ __('Name in arabic') }} *" placeholder="{{ __('Name in arabic') }}"/>


                <x-form.date required type="text" id="date" name="date"
                             wire:model.lazy="date"
                             autocomplete="off"
                             :error="$errors->get('date')"
                             label="{{ __('Until') }}" size="col-span-1" />

                <x-form.gender-switch :livewire="true" name="gender" id="guest-gender" label="{{ __('Gender') }}"/>
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
</div>
