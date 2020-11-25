<x-master>
    <x-slot name="title">Create Admin | Ticket</x-slot>

    <x-card>
        {!! Form::open(["url" => url("admins"), "method" => "POST"]) !!}

        @include("admins.form", ["create" => true, "submit" => "Create Admin"])

        {!! Form::close() !!}
    </x-card>

</x-master>

