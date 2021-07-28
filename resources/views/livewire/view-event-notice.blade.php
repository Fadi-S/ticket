<div>

    <div class="flex items-center justify-center">
        <x-svg.spinner wire:loading />
    </div>

    <div>
        <div wire:loading.remove>

            <form wire:submit.prevent="save">

                <x-form.textarea class="mb-4" id="notice"
                                 wire:model.defer="event.notice" />

                <x-button type="submit">
                    <x-slot name="svg">
                        <x-svg.spinner wire:loading wire:target="save" />

                        <x-svg.check wire:loading.remove wire:target="save" />
                    </x-slot>

                    {{ __('Save') }}
                </x-button>

            </form>

            <div class="mt-4">
                @if(session()->has('success'))
                    <x-layouts.success>{{ session('success') }}</x-layouts.success>
                @endif
            </div>

        </div>
    </div>

</div>
