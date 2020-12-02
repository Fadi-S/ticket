<div class="space-y-6">
    <x-form.group>

        <x-form.select name="users[]" :multiple="true" size="w-1/2"
                       id="user" :checked="$create ? [] : [$reservation->user->id]"
                       label="Choose Users" style="width: 100%;"
                       :options="$create ? [] : $users" />


        <x-form.select name="event" size="w-1/2"
                       label="Event" readonly="readonly" id="event"
                       :options="$create ? [] : [$reservation->event->id => $reservation->event->start->format('d/m/Y h:i A')
                       . ' | '. $reservation->event->type->arabic_name]"
        />
    </x-form.group>

    <div id='calendar'></div>

    <x-button type="submit" class="mx-auto mt-2">
        <x-slot name="svg"><x-svg.edit /></x-slot>
        {{ $submit }}
    </x-button>

    <x-layouts.errors />
</div>

@push("scripts")

    {!! Html::script("js/select2.full.min.js") !!}
    {!! Html::style("css/select2.min.css") !!}

    {!! Html::script("js/fullcalendar.min.js") !!}
    {!! Html::style("css/fullcalendar.min.css") !!}

    <script>
        let lastSelected;

        function selectCurrentEvent(startDate, endDate) {
            if (lastSelected != null)
                lastSelected.css({"backgroundColor": "", "color": ""});

            lastSelected = $("td[data-date='" + startDate.format('YYYY-MM-DD') + "'] a.fc-daygrid-event div:contains('" + formatDate(startDate) + "')")
                .add("td[data-date='" + endDate.format('YYYY-MM-DD') + "'] a.fc-daygrid-event div:contains('" + formatDate(endDate) + "')")
                .parent()
                .css({"backgroundColor": "green", "color": "white"});
        }

        function formatDate(date) {
            let dateString = date.format("h:mma");

            if (date.format("m") === '0')
                dateString = date.format("ha");

            return dateString;
        }

        $(document).ready(function () {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                weekNumbers: true,
                height: 650,
                expandRows: false,
                events: '{{ url("api/reservation/events") }}',
                eventClick: function (event) {
                    let eventInput = $("#event");

                    eventInput.find('option').remove().end();

                    let date = new Date(event.event.start);
                    let dateWrapper = moment(date);

                    let endDate = new Date(event.event.end);
                    let endDateWrapper = moment(endDate);

                    let datetime = dateWrapper.format("DD/MM/YYYY hh:mm A") + " | " + event.event.title;

                    eventInput.append('<option selected value="' + event.event.id + '">' + datetime + '</option>');

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

            $('#user').select2({
                ajax: {
                    url: '{{ url("api/reservation/users") }}',
                    dataType: 'json',
                    method: "get",
                    data: function (params) {
                        return {
                            search: params.term,
                            type: 'public'
                        }
                    }
                },
                width: 'resolve'
            });

            @if(!$create)
                $("#user").prop("disabled", true);
            @endif

        });
    </script>

    <style>
        .fc-daygrid-event, .fc-list-event {
            cursor: pointer;
        }
    </style>

@endpush
