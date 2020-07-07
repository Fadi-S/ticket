@extends("master")

@section("title")
    <title>Create Admin | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Create Admin Form</h6>
                    <div class="mT-30">
                        {!! Form::open(["url" => url("admins"), "method" => "POST"]) !!}

                        @include("admins.form", ["create" => true, "submit" => "Create Admin"])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
