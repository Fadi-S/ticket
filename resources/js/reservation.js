import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import arLocale from '@fullcalendar/core/locales/ar';

let lastSelected;
let lastSelectedStyle = {};

function selectCurrentEvent(date, element=null) {
    if (lastSelected != null) {
        lastSelected.style.backgroundColor = lastSelectedStyle.backgroundColor ?? '';
        lastSelected.style.color = lastSelectedStyle.color ?? '';
    }

    lastSelected = element;

    if(! lastSelected) {
        if(! date) {
            return;
        }

        const formatDateToTime = date => {
            let hours = date.getHours();
            let minutes = "0" + date.getUTCMinutes();
            let a = 'am';

            if(hours > 12) {
                hours -= 12;
                a = 'pm';
            }

            hours = hours.toString();
            minutes = minutes.substr(minutes.length-2);

            return (date.getUTCMinutes() === 0) ? hours + a : hours + ":" + minutes + a;
        };

        const contains = (selector, text) => {
            let elements = document.querySelectorAll(selector);
            return [].filter.call(elements, element => RegExp(text).test(element.textContent));
        };

        let formattedDate = date.toISOString().split('T')[0]; // YYYY-MM-DD
        let formattedTime = formatDateToTime(date); // H:mma or Ha

        lastSelected = contains("td[data-date='" + formattedDate + "'] a.fc-daygrid-event div",  formattedTime + " -")[0]?.parentElement;

        lastSelected ??= contains("td[data-date='" + formattedDate + "'] a.fc-timegrid-event div", formattedTime + " -")[0];

        if(! lastSelected) return;
    }

    lastSelectedStyle.backgroundColor = lastSelected.style.backgroundColor;
    lastSelectedStyle.color = lastSelected.style.color;

    lastSelected.classList.add('transition-colors', 'duration-150');
    lastSelected.style.backgroundColor = '#129406';
    lastSelected.style.color = 'white';

    return lastSelected;
}

window.addEventListener('load', () => {

    if(!document.getElementById('calendar')) {
        return;
    }

    let startDate;

    let locale = window.locale ?? 'en';

    const determineView = () => {
        if(window.innerWidth < 640) {
            return  'timeGridSmall';
        }else if(window.innerWidth < 1024) {
            return 'timeGridMedium';
        } else if(window.innerWidth < 1450) {
            return 'timeGridLarge';
        }

        return 'dayGridMonth';
    };

    window.calendar = new Calendar(document.getElementById('calendar'), {
        plugins: [ dayGridPlugin, timeGridPlugin ],
        locales: [arLocale],
        locale: locale,
        initialView: determineView(),
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
        views: {
            timeGridLarge: {
                type: 'timeGridWeek',
                duration: { days: 4 },
                buttonText: '4 day'
            },
            timeGridMedium: {
                type: 'timeGridWeek',
                duration: { days: 2 },
                buttonText: '2 day'
            },
            timeGridSmall: {
                type: 'timeGridWeek',
                duration: { days: 1 },
                buttonText: '1 day'
            },
        },
        displayEventEnd: true,
        eventClick(event) {
            startDate = new Date(event.event.start);

            window.livewire.emit('set:event', event.event.id);

            selectCurrentEvent(startDate, event.el);
        },
        eventsSet() {
            selectCurrentEvent(startDate);
        }
    });
    calendar.render();

    Echo.channel('tickets')
        .listen('TicketReserved', () => calendar.refetchEvents());

    window.addEventListener('resize', () => {
        let newView = determineView();

        if(newView !== calendar.view.type) {
            calendar.changeView(newView);

            selectCurrentEvent(startDate);
        }

    });

});
