<x-master>
    <x-slot name="title">Edit Mass | Ticket</x-slot>

    <x-card>
        <div class="text-3xl font-bold text-gray-700">
            {{ $event->type->name }}
        </div>

        {!! Form::model($event, ["url" => url("$url/$event->id/"), "method" => "PATCH"]) !!}

        @include("events.form", ["create" => false, "submit" => __('Edit Event')])

        {!! Form::close() !!}
    </x-card>
</x-master>
