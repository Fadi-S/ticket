<x-master>
    <x-slot name="title">Create Mass | Ticket</x-slot>

    <x-card>
        {!! Form::open(["url" => url("masses"), "method" => "POST"]) !!}

        @include("mass.form", ["create" => true, "submit" => "Create Mass"])

        {!! Form::close() !!}
    </x-card>
</x-master>
