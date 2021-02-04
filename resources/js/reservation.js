import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
window.jQuery = window.$ = require('jquery');

window.moment = require('moment');

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

document.addEventListener('turbolinks:load', () => {

    let calendarEl = document.getElementById('calendar');
    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin ],
        initialView: window.innerWidth > 768 ? 'dayGridMonth' : 'timeGridWeek',
        slotMinTime: '06:00:00',
        allDaySlot: false,
        weekNumbers: true,
        height: 650,
        expandRows: false,
        events: '/ajax/reservation/events',
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            omitZeroMinute: true,
            meridiem: 'short'
        },
        displayEventEnd: true,
        eventClick(event) {
            let date = new Date(event.event.start);
            let dateWrapper = window.moment(date);

            let endDate = new Date(event.event.end);
            let endDateWrapper = window.moment(endDate);

            window.livewire.emit('set:event', event.event.id);

            currentEvent = event;

            selectCurrentEvent(dateWrapper, endDateWrapper, event.el);
        },
    });
    calendar.render();

    window.addEventListener('resize', () => {
        let newView = window.innerWidth > 1024 ? 'dayGridMonth' : 'timeGridWeek';

        if(newView !== calendar.view.type)
            calendar.changeView(newView);
    });

});