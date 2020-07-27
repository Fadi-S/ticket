<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Name</label>
        {!! Form::text("name", null, ["class" => "form-control", "id" => "name", "placeholder" => "Name"]) !!}
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <label for="value">Value</label>
        {!! Form::textarea("value", null, ["class" => "form-control", "id" => "value", "placeholder" => "Ex: Array, String, Number, etc.."]) !!}
    </div>
</div>

<center>
    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> {{ $submit }}</button>
</center>

@include("errors.list")
