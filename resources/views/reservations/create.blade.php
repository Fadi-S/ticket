<x-master>
    <x-slot name="title">Make Reservation | Ticket</x-slot>

    <x-card>
        <form action="{{ url('/reservations') }}" method="POST">
            @csrf

            @include("reservations.form", ["create" => true, "submit" => "Make Reservation"])
        </form>
    </x-card>

</x-master>
