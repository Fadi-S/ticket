<x-slot name="title">
    Announcement | Ticket
</x-slot>

<div>
    <x-card>

        <form wire:submit.prevent="save" method="POST">
            <div class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <x-form.input required type="text"
                                  wire:model="announcement.title"
                                  :error="$errors->get('announcement.title')"
                                  id="announcement-title" name="announcement-title" autocomplete="off"
                                  label="{{ __('Title') }}"
                                  size="col-span-1"
                                  placeholder="{{ __('Title') }}" />

                    <x-form.input required type="color" id="color" name="color"
                                  wire:model.lazy="announcement.color"
                                  autocomplete="off"
                                  :error="$errors->get('announcement.color')"
                                  placeholder="{{ __('Color') }}"
                                  label="{{ __('Color') }}" size="col-span-1" />

                    <x-form.date required type="text" id="announcement-start" name="announcement-start"
                                 wire:model.lazy="start"
                                 autocomplete="off"
                                 :time="true"
                                 :error="$errors->get('start')"
                                 label="{{ __('Start Time') }}" size="col-span-1" />

                    <x-form.date required type="text" id="announcement-end" name="announcement-end"
                                 wire:model.lazy="end"
                                 autocomplete="off"
                                 :time="true"
                                 :error="$errors->get('end')"
                                 label="{{ __('End Time') }}" size="col-span-1" />

                    <x-form.input type="text" id="url" name="url"
                                  wire:model.lazy="announcement.url"
                                  autocomplete="off"
                                  dir="ltr"
                                  :error="$errors->get('announcement.url')"
                                  placeholder="{{ __('URL') }}"
                                  label="{{ __('URL') }}" size="col-span-1" />

                    <x-form.textarea required id="announcement-body" name="announcement-body"
                                 wire:model.lazy="announcement.body"
                                 autocomplete="off"
                                 :error="$errors->get('announcement.body')"
                                 label="{{ __('Body') }}" size="col-span-2" />

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
