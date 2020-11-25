<x-master>
    <x-slot name="title">Make Reservation | Ticket</x-slot>

    <x-card>
        {!! Form::open(["url" => url("reservations"), "method" => "POST"]) !!}

        @include("reservations.form", ["create" => true, "submit" => "Make Reservation"])

        {!! Form::close() !!}
    </x-card>

</x-master>
