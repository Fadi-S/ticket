<div>
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
                @unless(config('settings.arabic_name_only'))
                    <x-form.input wire:model.lazy="user.name" required type="text"
                                  :error="$errors->get('user.name')"
                                  size="col-span-1" name="name" id="name" dir="ltr"
                                  label="{{ __('Name in english') }} *" placeholder="{{ __('Name in english') }}"/>
                @endunless

                <x-form.input wire:model.lazy="user.arabic_name"
                              required type="text"
                              :error="$errors->get('user.arabic_name')"
                              size="col-span-1" name="arabic_name"
                              id="arabic_name"
                              dir="rtl"
                              label="{{ __('Name in arabic') }} *" placeholder="{{ __('Name in arabic') }}"/>


                <x-form.date required type="text" id="date" name="date"
                             wire:model.lazy="date"
                             autocomplete="off"
                             :error="$errors->get('date')"
                             label="{{ __('Until') }}" size="col-span-1" />

                <x-form.gender-switch :livewire="true" name="gender" id="gender" label="{{ __('Gender') }}"/>
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
</div>
