<x-master>
    <x-slot name="title">Edit Mass | Ticket</x-slot>

    <x-card>
        {!! Form::model($mass, ["url" => url("masses/$mass->id/"), "method" => "PATCH"]) !!}

        @include("mass.form", ["create" => false, "submit" => "Edit Mass"])

        {!! Form::close() !!}
    </x-card>
</x-master>
