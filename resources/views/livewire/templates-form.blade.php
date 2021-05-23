<div>
    <x-card>

        <x-form.switch wire:model="enabled" />

        <form wire:submit.prevent="save" method="POST">
            <div class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <x-form.input required type="text"
                                  wire:model="template.description"
                                  :error="$errors->get('template.description')"
                                  id="description" name="description" autocomplete="off"
                                  label="{{ __('Description') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Description') }}" />

                    <x-form.input required type="number" id="places"
                                  wire:model="template.number_of_places"
                                  :error="$errors->get('template.number_of_places')"
                                  name="number_of_places" label="{{ __('Number of Places') }}" class="col-span-1"
                                  size="col-span-1" min="1" placeholder="{{ __('Number of Places') }}" />

                    <x-form.input required type="number" id="deacon_places"
                                  wire:model="template.deacons_number"
                                  :error="$errors->get('template.deacons_number')"
                                  name="deacon_places" label="{{ __('Deacon Places') }}" class="col-span-1"
                                  size="col-span-1" min="0" placeholder="{{ __('Deacon Places') }}" />

                    <x-form.input required type="number" id="overload"
                                  wire:model="template.overload"
                                  :error="$errors->get('template.overload')"
                                  name="overload" label="{{ __('Allowed Overload by admins %') }}" class="col-span-1"
                                  size="col-span-1" min="0" max="100" placeholder="20%" />

                    <x-form.select wire:model="template.day_of_week" name="day_of_week"
                                   :error="$errors->get('template.day_of_week')"
                                   id="day_of_week" size="col-span-1"
                                   label="{{ __('Day Of Week') }}" :options="[
                                        -1 => '-',
                                        0 => 'Sunday',
                                        1 => 'Monday',
                                        2 => 'Tuesday',
                                        3 => 'Wednesday',
                                        4 => 'Thursday',
                                        5 => 'Friday',
                                        6 => 'Saturday',
                                   ]" />

                    <x-form.input required type="time" id="start_time" name="start_time"
                                  wire:model="start"
                                  :error="$errors->get('start')"
                                  label="{{ __('Start Time') }}" size="col-span-1" />

                    <x-form.input required type="time" id="end_time" name="end_time"
                                  wire:model="end"
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
