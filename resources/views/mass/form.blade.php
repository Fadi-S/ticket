<div class="form-row">
    <div class="form-group col-md-6">
        <label for="date">Date</label>
        {!! Form::text("date", null, ["class" => "form-control datePicker", "id" => "date", "placeholder" => "Date"]) !!}
    </div>

    <div class="form-group col-md-6">
        <label for="places">Number of Places</label>
        {!! Form::number("number_of_places", $create ? 1 : null, ["class" => "form-control", "id" => "places", "placeholder" => "Number of places", "min" => 1]) !!}
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <label for="description">Description (Optional)</label>
        {!! Form::textarea("description", null, ["class" => "form-control", "id" => "description", "placeholder" => "Description"]) !!}
    </div>
</div>

<center>
    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> {{ $submit }}</button>
</center>

@include("errors.list")

@section("javascript")
    <script>
        $(".datePicker").daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            @if(!old("date"))
            startDate:  "{{ ($create) ? $date->format("d/m/Y h:i A") : $mass->time->format("d/m/Y h:i A") }}",
            @endif
            locale: {
                format: 'DD/MM/YYYY hh:mm A'
            }
        });
    </script>
@endsection
