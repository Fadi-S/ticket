<x-master>
    <x-slot name="title">Edit Mass | Ticket</x-slot>

    <x-card>
        <div class="text-3xl font-bold text-gray-700">
            {{ $event->type->name }}
        </div>

        {!! Form::model($event, ["url" => url("$url/$event->id/"), "method" => "DELETE", "id" => 'delete-event']) !!}
            @csrf
            <button type="button"
                    class="border border-red-500 rounded-md px-4 py-2 hover:bg-red-600 transition-dark mb-4"
                    x-data="{  }" @click="$dispatch('open')">{{ __("Delete Event") }}</button>

        @push('modals')
            <x-layouts.modal id="user-form-modal" size="w-full rounded-none sm:rounded-lg md:max-w-2xl
                 lg:max-w-4xl my-2 sm:max-w-xl" @open.window="open=true" @close.window="open=false">
                <x-slot name="dialog">
                    <div class="px-6 py-10 text-lg rtl:text-right mx-2 leading-6 font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Are you sure you want to delete that event?') }}
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <div class="space-x-2 flex flex-row-reverse">

                        <x-button class="mx-2" type="button" @click="open = false;"
                                  color="bg-white dark:bg-gray-500 dark:hover:bg-gray-700
                                       bg:text-gray-900 text-gray-700 dark:text-gray-200
                                       hover:bg-gray-50 border border-gray-400">
                            {{ __("Cancel") }}
                        </x-button>

                        <x-button class="mx-2" type="submit" form="delete-event"
                                  color="bg-red-500 hover:bg-red-600">
                            {{ __("Delete Event") }}
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
