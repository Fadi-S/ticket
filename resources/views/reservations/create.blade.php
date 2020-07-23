@extends("master")

@section("title")
    <title>Make Reservation | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Make Reservation Form</h6>
                    <div class="mT-30">
                        {!! Form::open(["url" => url("reservations"), "method" => "POST"]) !!}

                        @include("reservations.form", ["create" => true, "submit" => "Make Reservation"])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
