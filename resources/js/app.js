window.jQuery = window.$ = require('jquery');

import 'alpinejs';

window.Pikaday = require('pikaday');

window.moment = require('moment');

import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ticket_7612241942',
    wsHost: 'sockets.fadisarwat.dev',
    wsPort: 443,
    wssPort: 443,
    encrypted:true,
    enabledTransports: ['ws', 'wss'],
    forceTLS: true,
    disableStats: false,
});