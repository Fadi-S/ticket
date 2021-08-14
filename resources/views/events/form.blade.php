<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <x-form.date required type="text" :value="!$create ? $event->start->format('Y-m-d') : null"
                      id="date" name="date" autocomplete="off"
                      label="{{ __('Date') }}"
                      size="col-span-1"
                     :error="$errors->get('date')"
                      placeholder="DD/MM/YYYY" />

        <x-form.input required type="text" :value="!$create ? $event->description : null"
                      id="description" name="description" autocomplete="off"
                      label="{{ __('Description') }}"
                      size="col-span-1"
                      :error="$errors->get('description')"
                      placeholder="{{ __('Description') }}" />

        <x-form.date required type="text" :value="!$create ? $event->published_at->format('Y-m-d h:i A') : null"
                      id="date" name="published_at" autocomplete="off" id="published"
                      label="{{ __('Publish Date') }}"
                      size="col-span-1"
                     :time="true"
                     :error="$errors->get('published_at')"
                      placeholder="DD/MM/YYYY hh:mm A" />

        <x-form.input required type="number" id="places" :value="$create ? 150 : $event->number_of_places"
                      name="number_of_places" label="{{ __('Number of Places') }}" class="col-span-1"
                      :error="$errors->get('number_of_places')"
                      size="col-span-1" min="1" placeholder="{{ __('Number of Places') }}" />

        @if($type->has_deacons)
            <x-form.input required type="number" id="deacon_places" :value="$create ? 0 : $event->deacons_number"
                      name="deacons_number" label="{{ __('Deacon Places') }}" class="col-span-1" :error="$errors->get('deacons_number')"
                      size="col-span-1" min="0" placeholder="{{ __('Deacon Places') }}" />
        @endif

        <x-form.input required type="number" id="overload" :value="$create ? 20 : $event->overload*100"
                      name="overload" label="{{ __('Allowed Overload by admins %') }}" class="col-span-1"
                      :error="$errors->get('overload')"
                      size="col-span-1" min="0" placeholder="20%" />

        <x-form.input required type="time" id="start_time" name="start_time"
                      label="{{ __('Start Time') }}" size="col-span-1" :error="$errors->get('start_time')"
                      :value="$create ? null : $event->start->format('H:i')" />

        <x-form.input required type="time" id="end_time" name="end_time"
                      label="{{ __('End Time') }}" size="col-span-1" :error="$errors->get('end_time')"
                      :value="$create ? null : $event->end->format('H:i')" />
    </div>

    <x-button type="submit" class="mx-auto mt-2">
        <x-slot name="svg">
            <x-svg.edit />
        </x-slot>

        {{ $submit }}
    </x-button>

</div>
