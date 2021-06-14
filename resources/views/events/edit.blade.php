<x-master>
    <x-slot name="title">Edit Event | {{ config('app.name') }}</x-slot>

    <x-card>
        <div class="text-3xl font-bold text-gray-700 mb-4">
            {{ $event->type->name }}
        </div>

        {!! Form::model($event, ["url" => url("$url/$event->id/"), "method" => "DELETE", "id" => 'delete-event']) !!}
            @csrf
            <button type="button"
                    class="flex items-center border border-red-500 rounded-md px-4 py-2 hover:bg-red-600 transition-dark mb-4 focus:outline-none hover:text-white"
                    x-data="{  }" @click="$dispatch('open')">
                <div class="ltr:mr-2 rtl:ml-2">
                    <x-svg.trash />
                </div>
                {{ __("Delete Event") }}
            </button>

        @push('modals')
            <x-layouts.modal id="user-form-modal" size="w-full rounded-none sm:rounded-lg md:max-w-2xl
                 lg:max-w-4xl my-2 sm:max-w-xl" @open.window="open=true" @close.window="open=false">
                <x-slot name="svg">
                    <x-svg.trash color="text-red-600" />
                </x-slot>

                <x-slot name="body">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 flex items-center sm:justify-start sm:mr-3 justify-center" id="modal-headline">
                        {{ __('Deleting Event') }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-300">
                            {{ __('Are you sure you want to delete that event?') }}
                        </p>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <div class="space-x-2 flex justify-between">

                        <x-button class="mx-2" type="button" @click="open = false;"
                                  color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       bg:text-gray-900 text-gray-700 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                            {{ __("Cancel") }}
                        </x-button>

                        <x-button type="submit" form="delete-event"
                                  color="bg-red-500 hover:bg-red-600 dark:bg-red-700
                                   dark:hover:bg-red-500 text-white">
                            <x-slot name="svg">
                                <x-svg.trash />
                            </x-slot>
                            {{ __('Delete Event') }}
                        </x-button>
                    </div>
                </x-slot>
            </x-layouts.modal>
        @endpush

        {!! Form::close() !!}

        {!! Form::model($event, ["url" => url("$url/$event->id/"), "method" => "PATCH"]) !!}

        @include("events.form", ["create" => false, "submit" => __('Edit Event')])

        {!! Form::close() !!}
    </x-card>
</x-master>
