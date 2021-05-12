<div>
    <form wire:submit.prevent="generate">

        <div class="space-y-6">

            <div class="text-xl text-center font-semibold dark:text-white text-gray-800">
                {{ __('Generate events from available templates, you must choose start and end dates to generate events between them.') }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                <x-form.date required type="text" id="publish_at" name="publish_at_time"
                             wire:model.lazy="publish_at"
                             autocomplete="off"
                             :error="$errors->get('publish_at')"
                             label="{{ __('Publish Date') }}" size="col-span-1" />
            </div>

            <x-button type="submit">
                <x-slot name="svg">
                    <x-svg.check size="h-6 w-6" wire:loading.remove wire:target="generate" />
                    <x-svg.spinner wire:loading wire:target="generate" />
                </x-slot>

                {{ __('Generate Events') }}
            </x-button>
        </div>
    </form>
</div>
