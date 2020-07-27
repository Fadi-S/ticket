<div class="form-row">

    <div class="form-group col-md-6">
        <label for="user">Choose Users</label>
        {!! Form::select("users[]", $create ? [] : $users, $create ? null : $reservation->users()->pluck("id"), ["class" => "form-control user", "multiple"=>"multiple", "id"=>"user"]) !!}
    </div>

    <div class="form-group col-md-6">
        <label for="event">Event</label>
        {!! Form::select("event", $create ? [] : [$reservation->event->id => $reservation->event->start->format("d/m/Y h:i A") . " | " . $reservation->event->type->arabic_name], null, ["class" => "form-control", "readonly" => "readonly", "id"=>"event"]) !!}
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
        let lastSelected;
        function selectCurrentEvent(startDate, endDate) {
            let timeString = startDate.format("h:mma");
            if(startDate.format("m") == 0)
                timeString = startDate.format("ha");

            if(endDate.format("m") == 0)
                timeString = timeString + " - " + endDate.format("ha");
            else
                timeString = timeString + " - " + endDate.format("h:mma");

            let dateString = startDate.format('YYYY-MM-DD');

            if(lastSelected != null)
                lastSelected.css({"backgroundColor": "", "color": ""});

            lastSelected = $("td[data-date='" + dateString + "'] a.fc-daygrid-event div:contains('" + timeString + "')")
                .parent()
                .css({"backgroundColor": "green", "color":"white"});
        }

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

                    let date = new Date(event.event.start);
                    let dateWrapper = moment(date);

                    let endDate = new Date(event.event.end);
                    let endDateWrapper = moment(endDate);

                    let datetime = dateWrapper.format("DD/MM/YYYY hh:mm A") + " | " + event.event.title;

                    eventInput.append('<option selected value="' + event.event.id +'">' + datetime + '</option>');

                    selectCurrentEvent(dateWrapper, endDateWrapper);
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

            @if(!$create)
                setTimeout(function () {
                    selectCurrentEvent(
                        moment(new Date("{{ $reservation->event->start->format("Y-m-d h:i A") }}")),
                        moment(new Date("{{ $reservation->event->end->format("Y-m-d h:i A") }}")));
                }, 500);
            @endif

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
