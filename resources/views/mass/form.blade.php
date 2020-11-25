<div class="space-y-6">

    <x-form.group>

        <x-form.input required type="text" :value="!$create ? $mass->start->format('d/m/Y') : null"
                      id="date" name="date" autocomplete="off"
                      label="Date" class="datePicker"
                      size="w-1/2"
                      placeholder="DD/MM/YYYY" />

        <x-form.input required type="number" id="places" :value="$create ? 1 : $mass->number_of_places"
                      name="number_of_places" label="Number of Places"
                      size="w-1/2" min="1" placeholder="Number Of Places" />
    </x-form.group>

    <x-form.group>

        <x-form.input required type="time" id="start_time" name="start_time" label="Start Time" size="w-1/2"
                      :value="$create ? null : $mass->start->format('H:i')" />

        <x-form.input required type="time" id="end_time" name="end_time" label="End Time" size="w-1/2"
                      :value="$create ? null : $mass->end->format('H:i')" />
    </x-form.group>

    <x-form.group>
        <x-form.textarea rows="8" id="description" name="description" :value="!$create ? $mass->description : null"
                         placeholder="Description" label="Description (Optional)" />
    </x-form.group>

    <x-button type="submit" class="mx-auto mt-2">
        <x-slot name="svg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
            </svg>
        </x-slot>

        {{ $submit }}
    </x-button>

    @include("errors.list")
</div>

@push("scripts")
    <script>
        new Pikaday({
            field: $('#date')[0],
            format: 'DD/MM/YYYY',
        });
    </script>
@endpush
