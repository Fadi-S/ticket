<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <x-form.input required type="text" :value="!$create ? $event->start->format('d/m/Y') : null"
                      id="date" name="date" autocomplete="off"
                      label="{{ __('Date') }}" class="datePicker"
                      size="col-span-1"
                      placeholder="DD/MM/YYYY" />

        <x-form.input required type="text" :value="!$create ? $event->description : null"
                      id="description" name="description" autocomplete="off"
                      label="{{ __('Description') }}"
                      size="col-span-1"
                      placeholder="{{ __('Description') }}" />

        <x-form.input required type="text" :value="!$create ? $event->published_at->format('d/m/Y h:i A') : null"
                      id="date" name="published_at" autocomplete="off" id="published"
                      label="{{ __('Publish Date') }}" class="datePicker"
                      size="col-span-1"
                      placeholder="DD/MM/YYYY hh:mm A" />

        <x-form.input required type="number" id="places" :value="$create ? 150 : $event->number_of_places"
                      name="number_of_places" label="{{ __('Number of Places') }}" class="col-span-1"
                      size="col-span-1" min="1" placeholder="{{ __('Number of Places') }}" />


        <x-form.input required type="number" id="overload" :value="$create ? 20 : $event->overload*100"
                      name="overload" label="{{ __('Allowed Overload by admins %') }}" class="col-span-1"
                      size="col-span-1" min="0" max="100" placeholder="20%" />

        <x-form.input required type="time" id="start_time" name="start_time"
                      label="{{ __('Start Time') }}" size="col-span-1"
                      :value="$create ? null : $event->start->format('H:i')" />

        <x-form.input required type="time" id="end_time" name="end_time"
                      label="{{ __('End Time') }}" size="col-span-1"
                      :value="$create ? null : $event->end->format('H:i')" />
    </div>

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
            field: document.querySelector('#date'),
            format: 'DD/MM/YYYY',
            isRTL: {{ __('ltr') === 'rtl' ? 'true' : 'false' }},
            showTime: false,
        });

        new Pikaday({
            field: document.querySelector('#published'),
            format: 'DD/MM/YYYY hh:mm A',
            isRTL: {{ __('ltr') === 'rtl' ? 'true' : 'false' }},
            showTime: true,
            showMinutes: false,
            use24hour: false,
        });
    </script>
@endpush
