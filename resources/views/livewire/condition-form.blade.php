<div>
    <x-card>

        <form wire:submit.prevent="save" method="POST">
            <div class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <x-form.input required type="text"
                                  wire:model="condition.name"
                                  :error="$errors->get('condition.name')"
                                  id="description" name="description" autocomplete="off"
                                  label="{{ __('Name') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Name') }}" />

                    <x-form.input required type="text" id="path" name="path"
                                 wire:model.lazy="condition.path"
                                 autocomplete="off"
                                 :error="$errors->get('condition.path')"
                                  placeholder="{{ __('Path') }}"
                                 label="{{ __('Path') }}" size="col-span-1" />

                    <x-form.input required type="number" id="priority" name="priority"
                                  wire:model.lazy="condition.priority"
                                  autocomplete="off" min="1"
                                  :error="$errors->get('condition.priority')"
                                  placeholder="{{ __('Priority') }}"
                                  label="{{ __('Priority') }}" size="col-span-1" />

                    <x-form.switch wire:model="required" :label="__('Is Required?')" />
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
