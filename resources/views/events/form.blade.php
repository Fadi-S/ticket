<div class="space-y-6">

    <x-form.group>

        <x-form.input required type="text" :value="!$create ? $event->start->format('d/m/Y') : null"
                      id="date" name="date" autocomplete="off"
                      label="Date" class="datePicker"
                      size="w-1/2"
                      placeholder="DD/MM/YYYY" />

        <x-form.input required type="number" id="places" :value="$create ? 1 : $event->number_of_places"
                      name="number_of_places" label="Number of Places"
                      size="w-1/2" min="1" placeholder="Number Of Places" />
    </x-form.group>

    <x-form.group>

        <x-form.input required type="time" id="start_time" name="start_time" label="Start Time" size="w-1/2"
                      :value="$create ? null : $event->start->format('H:i')" />

        <x-form.input required type="time" id="end_time" name="end_time" label="End Time" size="w-1/2"
                      :value="$create ? null : $event->end->format('H:i')" />
    </x-form.group>

    <x-form.group>
        <x-form.textarea rows="8" id="description" name="description" :value="!$create ? $event->description : null"
                         placeholder="Description" label="Description (Optional)" />
    </x-form.group>

    <x-button type="submit" class="mx-auto mt-2">
        <x-slot name="svg">
            <x-svg.edit />
        </x-slot>

        {{ $submit }}
    </x-button>

    <x-layouts.errors />
</div>

@push("scripts")
    <script>
        new Pikaday({
            field: $('#date')[0],
            format: 'DD/MM/YYYY',
        });
    </script>
@endpush
