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