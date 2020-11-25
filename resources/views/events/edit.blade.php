<x-master>
    <x-slot name="title">Edit Mass | Ticket</x-slot>

    <x-card>
        {!! Form::model($event, ["url" => url("$url/$event->id/"), "method" => "PATCH"]) !!}

        @include("events.form", ["create" => false, "submit" => "Edit Event"])

        {!! Form::close() !!}
    </x-card>
</x-master>
