<div class="form-row">

    <div class="form-group col-md-6">
        <label for="user">Choose Users</label>
        {!! Form::select("users[]", $create ? [] : $users, $create ? null : $reservation->users()->pluck("id"), ["class" => "form-control user", "multiple"=>"multiple", "id"=>"user"]) !!}
    </div>

    <div class="form-group col-md-6">
        <label for="event">Event</label>
        {!! Form::select("event", $create ? [] : [$reservation->event->id => $reservation->event->start->format("Y-m-d H:i")], null, ["class" => "form-control", "readonly" => "readonly", "id"=>"event"]) !!}
    </div>

</div>

<div id='calendar'></div>

<br>

<center>
    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> {{ $submit }}</button>
</center>

@include("errors.list")

@section("javascript")

    {!! Html::script("js/select2.full.min.js") !!}
    {!! Html::style("css/select2.min.css") !!}

    {!! Html::script("js/fullcalendar.min.js") !!}
    {!! Html::style("css/fullcalendar.min.css") !!}

    <script>
        $(document).ready(function () {

            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                weekNumbers: true,
                height: 650,
                expandRows: true,
                events: '{{ url("api/reservation/events") }}',
                eventClick: function(event) {
                    let eventInput = $("#event");

                    eventInput.find('option').remove().end();

                    let date = new Date(event.event.start)

                    let month = ("0" + (date.getMonth() + 1)).slice(-2),
                        day = ("0" + date.getDate()).slice(-2);

                    let time = [date.getFullYear(), month, day].join("-") + " " + ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2);

                    eventInput.append('<option selected value="' + event.event.id +'">' + time + '</option>');
                },
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: true,
                    meridiem: 'short'
                },
                displayEventEnd: true,
            });
            calendar.render();

            $('.user').select2({
                ajax: {
                    url: '{{ url("api/reservation/users") }}',
                    dataType: 'json',
                    method: "get",
                    data: function (params) {
                        return  {
                            search: params.term,
                            type: 'public'
                        }
                    }
                }
            });

        });
    </script>

    <style>
        .fc-daygrid-event, .fc-list-event {
            cursor: pointer;
        }
    </style>

@endsection
