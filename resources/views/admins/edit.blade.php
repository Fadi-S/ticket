<x-master>
    <x-slot name="title">Edit Admin | Ticket</x-slot>

    <x-card>
        {!! Form::model($admin, ["url" => url("admins/$admin->username/"), "method" => "PATCH"]) !!}

        @include("admins.form", ["create" => false, "submit" => "Edit Admin"])

        {!! Form::close() !!}
    </x-card>

</x-master>

