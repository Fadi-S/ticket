@extends("master")

@section("title")
    <title>Edit User | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Edit User Form</h6>
                    <div class="mT-30">
                        {!! Form::model($user, ["url" => url("users/$user->username/"), "method" => "PATCH"]) !!}

                        @include("users.form", ["create" => false, "submit" => "Edit User"])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
