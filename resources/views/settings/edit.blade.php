@extends("master")

@section("title")
    <title>Edit Setting | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Edit Setting Form</h6>
                    <div class="mT-30">
                        <strong class="c-red-500 mx-auto">Be careful editing this, the website could break</strong>
                        <br>
                        {!! Form::model($setting, ["url" => url("settings/$setting->id/"), "method" => "PATCH"]) !!}

                        @include("settings.form", ["create" => false, "submit" => "Edit Setting"])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
