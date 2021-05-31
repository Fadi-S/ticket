<x-slot name="title">
    {{ __('Types Form | Ticket') }}
</x-slot>

<div>
    <x-card>

        <form wire:submit.prevent="save" method="POST">
            <div class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <x-form.input required type="text"
                                  wire:model="type.name"
                                  :error="$errors->get('type.name')"
                                  id="description" name="description" autocomplete="off"
                                  label="{{ __('Name') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Name') }}" />

                    <x-form.input required type="text"
                                  wire:model="type.arabic_name"
                                  :error="$errors->get('type.arabic_name')"
                                  id="arabic_name" name="arabic_name" autocomplete="off"
                                  label="{{ __('Arabic Name') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Arabic Name') }}" />

                    <x-form.input required type="text"
                                  wire:model="type.plural_name"
                                  :error="$errors->get('type.plural_name')"
                                  id="plural_name" name="plural_name" autocomplete="off"
                                  label="{{ __('Display Name') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Display Name') }}" />

                    <x-form.input required type="text" id="url" name="url"
                                  wire:model.lazy="type.url"
                                  autocomplete="off"
                                  :error="$errors->get('type.url')"
                                  placeholder="{{ __('URL') }}"
                                  label="{{ __('URL') }}" size="col-span-1" />

                    <x-form.input required type="number" id="max_reservations" name="max_reservations"
                                  wire:model.lazy="type.max_reservations"
                                  autocomplete="off" min="-1"
                                  :error="$errors->get('type.max_reservations')"
                                  placeholder="{{ __('Max Reservations') }}"
                                  label="{{ __('Max Reservations') }}" size="col-span-1" />

                    <x-form.input required type="color" id="color" name="color"
                                  wire:model.lazy="type.color"
                                  autocomplete="off"
                                  :error="$errors->get('type.color')"
                                  placeholder="{{ __('Color') }}"
                                  label="{{ __('Color') }}" size="col-span-1" />

                    <x-form.switch wire:model="show" :label="__('Shown')" />

                    <x-form.switch wire:model="deacons" :label="__('Has Deacons')" />
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
            </div>
        </form>

    </x-card>
</div>
