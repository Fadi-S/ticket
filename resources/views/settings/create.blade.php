@extends("master")

@section("title")
    <title>Create Setting | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Create Setting Form</h6>
                    <div class="mT-30">
                        {!! Form::open(["url" => url("settings"), "method" => "POST"]) !!}

                        @include("settings.form", ["create" => true, "submit" => "Add Setting"])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
