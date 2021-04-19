import 'alpinejs';
import Echo from "laravel-echo";

window.Pikaday = require('pikaday-time');

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    encrypted: true,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
});

window.addEventListener('dark', (event) => {
    let darkMode = event.detail.enable;

    setCookie('dark', darkMode, 365 * 10);
});

window.setCookie = (cname, value, expiryInDays) => {
    let d = new Date();
    d.setTime(d.getTime() + (expiryInDays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + value + ";" + expires + ";path=/";
};

if ('serviceWorker' in navigator) {

    window.addEventListener('load', () => {

        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {

                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, err => {
                console.log('ServiceWorker registration failed: ', err);
            });

    });

}

window.getCookie = cname => {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
};

require('./intro.js');

window.englishToArabicNumbers = number => {
    const numbers = {'0': '٠', '1': '١', '2': '٢', '3': '٣', '4': '٤', '5': '٥', '6': '٦', '7': '٧', '8': '٨', '9': '٩'};

    for (const [key, value] of Object.entries(numbers)) {
        number = number.replaceAll(key, value);
    }

    return number;
}
