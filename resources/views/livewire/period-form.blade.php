<div>
    <x-card>

        <form wire:submit.prevent="save" method="POST">
            <div class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <x-form.input required type="text"
                                  wire:model="period.name"
                                  :error="$errors->get('period.name')"
                                  id="description" name="description" autocomplete="off"
                                  label="{{ __('Name') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Name') }}" />

                    <x-form.select required
                                  wire:model="period.type_id"
                                  :error="$errors->get('period.type_id')"
                                  id="type_id" name="type_id"
                                  label="{{ __('Event Type') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Event Type') }}"
                                  :options="$types" />

                    <x-form.date required type="text" id="start" name="start_time"
                                 wire:model.lazy="start"
                                 autocomplete="off"
                                 :error="$errors->get('start')"
                                 label="{{ __('Start Time') }}" size="col-span-1" />

                    <x-form.date required type="text" id="end" name="end_time"
                                  wire:model.lazy="end"
                                  autocomplete="off"
                                  :error="$errors->get('end')"
                                  label="{{ __('End Time') }}" size="col-span-1" />
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
