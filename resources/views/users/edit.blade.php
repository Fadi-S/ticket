<x-master>
    <x-slot name="title">Edit User | Ticket</x-slot>

    <x-card>
        {!! Form::model($user, ["url" => url("users/$user->username/"), "method" => "PATCH"]) !!}

        @include("users.form", ["create" => false, "submit" => "Edit User"])

        {!! Form::close() !!}
    </x-card>

</x-master>
