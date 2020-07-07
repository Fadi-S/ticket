<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Name</label>
        {!! Form::text("name", null, ["class" => "form-control", "id" => "name", "placeholder" => "Name"]) !!}
    </div>

    <div class="form-group col-md-6">
        <label for="username">Username</label>
        {!! Form::text("username", null, ["class" => "form-control", "id" => "username", "placeholder" => "Username"]) !!}
        <small id="message"></small>
    </div>

</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="email">Email address</label>
        {!! Form::email("email", null, ["class" => "form-control", "id" => "email", "placeholder" => "Email"]) !!}
    </div>

    <div class="form-group col-md-6">
        <label for="password">Password</label>
        {!! Form::password("password", ["class" => "form-control", "id" => "password", "placeholder" => "Password"]) !!}
    </div>
</div>

<center>
    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> {{ $submit }}</button>
</center>

<input type="hidden" id="userId" value="{{ $create ? "0" : $user->id }}">
<input type="hidden" id="url" value="{{ url("/") }}">

@section("javascript")
    {!! Html::script("js/username-check.js") !!}
@endsection

@include("errors.list")
