@extends("master")

@section("title")
    <title>Edit Reservation | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Edit Reservation Form</h6>
                    <div class="mT-30">
                        {!! Form::model($reservation, ["url" => url("reservations/$reservation->id/"), "method" => "PATCH"]) !!}

                        @include("reservations.form", ["create" => false, "submit" => "Edit Reservation"])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
