<div class="space-y-6">
    <x-form.group class="md:flex-row flex-col md:space-x-2 md:space-y-0 space-x-0 space-y-2">

        <x-form.select name="users[]" :multiple="true" size="md:w-1/2 w-full"
                       id="user" :checked="$create ? (auth()->user()->isUser() ? [auth()->id()] : []) : [$reservation->user->id]"
                       label="Choose Users" style="width: 100%;"
                       :options="$users" />


        <x-form.select name="event" size="md:w-1/2 w-full" class="md:mt-0"
                       label="Event" readonly="readonly" id="event"
                       :options="$create ? [] : [$reservation->event->id => $reservation->event->start->format('d/m/Y h:i A')
                       . ' | '. $reservation->event->type->arabic_name]" />
    </x-form.group>

    <div id='calendar'></div>

    <x-button type="submit" class="mx-auto mt-2">
        <x-slot name="svg"><x-svg.edit /></x-slot>
        {{ $submit }}
    </x-button>

    <x-layouts.errors />
</div>

@push("scripts")

    <script src="{{ url('js/select2.full.min.js') }}"></script>
    <link media="all" type="text/css" rel="stylesheet" href="{{ url('css/select2.min.css') }}">

    <script src="{{ url('js/fullcalendar.min.js') }}"></script>
    <link media="all" type="text/css" rel="stylesheet" href="{{ url('css/fullcalendar.min.css') }}">
    <script src="{{ url('js/jquery.color.min.js') }}"></script>

    <script>
        let lastSelected;
        let currentEvent;

        function selectCurrentEvent(startDate, endDate, element) {
            if (lastSelected != null)
                lastSelected.css({"backgroundColor": ''});

            if(element != null) {
                lastSelected = $(element);
            }else {
                lastSelected = $("td[data-date='" + startDate.format('YYYY-MM-DD') + "'] a.fc-daygrid-event div:contains('" + formatDate(startDate) + "')")
                    .add("td[data-date='" + endDate.format('YYYY-MM-DD') + "'] a.fc-daygrid-event div:contains('" + formatDate(endDate) + "')")
                    .parent();
            }

            lastSelected.css({"backgroundColor":  "#82ffa2"});
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
                initialView: window.innerWidth > 768 ? 'dayGridMonth' : 'timeGridWeek',
                slotMinTime: '17:00:00',
                allDaySlot: false,
                weekNumbers: true,
                height: 650,
                expandRows: false,
                events: '{{ url("api/reservation/events") }}',
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: true,
                    meridiem: 'short'
                },
                displayEventEnd: true,
                eventClick: function (event) {
                    let eventInput = $("#event");

                    eventInput.find('option').remove().end();

                    let date = new Date(event.event.start);
                    let dateWrapper = moment(date);

                    let endDate = new Date(event.event.end);
                    let endDateWrapper = moment(endDate);

                    let datetime = dateWrapper.format("DD/MM/YYYY hh:mm A") + " | " + event.event.title;

                    eventInput.append('<option selected value="' + event.event.id + '">' + datetime + '</option>');

                    currentEvent = event;

                    selectCurrentEvent(dateWrapper, endDateWrapper, event.el);
                },
            });
            calendar.render();

            @if(!$create)
            setTimeout(function () {
                selectCurrentEvent(
                    moment(new Date("{{ $reservation->event->start->format("Y-m-d h:i A") }}")),
                    moment(new Date("{{ $reservation->event->end->format("Y-m-d h:i A") }}")),
                    null
                );
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
                width: 'resolve',
                language: {
                    noResults: () =>
                        '<a class="cursor w-full hover:bg-gray-200 focus:outline-none" ' +
                        'href="{{ url('/users/create?redirect=1') }}" target="_blank">This user is not found, Click here to create it</a>',
                },
                escapeMarkup: (markup) => markup,
            });

            window.addEventListener('resize', function () {
                let newView = window.innerWidth > 1024 ? 'dayGridMonth' : 'timeGridWeek';

                if(newView !== calendar.view.type)
                    calendar.changeView(newView);
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

        .fc-event-main {
            cursor: pointer;
        }
    </style>
@endpush
