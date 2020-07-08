@extends("master")

@section("title")
    <title>Edit Mass | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Edit Mass Form</h6>
                    <div class="mT-30">
                        {!! Form::model($mass, ["url" => url("masses/$mass->id/"), "method" => "PATCH"]) !!}

                        @include("mass.form", ["create" => false, "submit" => "Edit Mass"])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
