<x-master>
    <x-slot name="title">Edit Reservation | Ticket</x-slot>

    <div class="bg-white m-8 rounded-lg">
        <div class="bg-white p-20">
            <div class="mT-30">
                {!! Form::model($reservation, ["url" => url("reservations/$reservation->id/"), "method" => "PATCH"]) !!}

                @include("reservations.form", ["create" => false, "submit" => "Edit Reservation"])

                {!! Form::close() !!}
            </div>
        </div>

    </div>
</x-master>


