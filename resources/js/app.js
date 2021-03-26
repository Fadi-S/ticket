import 'alpinejs';
import './turbo-livewire';
import Echo from "laravel-echo";

window.Pikaday = require('pikaday');


window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: 'sockets.fadisarwat.dev',
    wsPort: 443,
    wssPort: 443,
    encrypted:true,
    enabledTransports: ['ws', 'wss'],
    forceTLS: true,
    disableStats: false,
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