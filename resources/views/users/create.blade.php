<x-master>
    <x-slot name="title">Create User | Ticket</x-slot>

    <x-card>
        {!! Form::open(["url" => url("users"), "method" => "POST"]) !!}

        @include("users.form", ["create" => true, "submit" => "Create User"])

        {!! Form::close() !!}
    </x-card>

</x-master>
