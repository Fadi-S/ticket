import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';

let lastSelected;
let currentEvent;


function contains(selector, text) {
    let elements = document.querySelectorAll(selector);
    return [].filter.call(elements, function(element){
        return RegExp(text).test(element.textContent);
    });
}

function selectCurrentEvent(startDate, endDate, element) {
    if (lastSelected != null) {
        lastSelected.style.backgroundColor = '';
        lastSelected.style.color = '';
    }

    if(element != null) {
        lastSelected = element;
    }else {
        lastSelected = contains("td[data-date='" + startDate.toISOString().split('T')[0] + "'] a.fc-daygrid-event div", formatDate(startDate) + " -")[0]?.parentElement;

        if(lastSelected == null)
            lastSelected = contains("td[data-date='" + startDate.toISOString().split('T')[0] + "'] a.fc-timegrid-event div", formatDate(startDate) + " -")[0];

        if(lastSelected == null)
            return;
    }

    lastSelected.classList.add('transition-colors', 'duration-150');
    lastSelected.style.backgroundColor = '#129406';
    lastSelected.style.color = 'white';
}

function formatDate(date) {
    let hours = date.getHours();
    let a = 'am';

    if(hours > 12) {
        hours -= 12;
        a = 'pm';
    }
    let minutes = "0" + date.getUTCMinutes();
    minutes = minutes.substr(minutes.length-2);

    let dateString = hours.toString() + ":" + minutes + a;

    if (date.getUTCMinutes() === 0)
        dateString = hours.toString() + a;

    return dateString;
}

document.addEventListener('turbolinks:load', () => {

    let startDate;
    let endDate;
    let element;

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
            startDate = new Date(event.event.start);

            endDate = new Date(event.event.end);

            window.livewire.emit('set:event', event.event.id);

            currentEvent = event;

            element = event.el;
            selectCurrentEvent(startDate, endDate, element);
        },
        eventSourceSuccess() {
            if(startDate != null)
                selectCurrentEvent(startDate, endDate, element);
        }
    });
    calendar.render();

    Echo.channel('tickets')
        .listen('TicketReserved', e => calendar.refetchEvents());

    window.addEventListener('resize', () => {
        let newView = window.innerWidth > 1024 ? 'dayGridMonth' : 'timeGridWeek';

        if(newView !== calendar.view.type) {
            calendar.changeView(newView);

            if(startDate != null)
                selectCurrentEvent(startDate, endDate, null);
        }

    });

});