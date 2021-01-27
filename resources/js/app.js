window.jQuery = window.$ = require('jquery');

import 'alpinejs';

window.Pikaday = require('pikaday');

window.moment = require('moment');

import Echo from "laravel-echo";

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

import firebase from "firebase/app";
import "firebase/auth";

const firebaseConfig = {
    apiKey: "AIzaSyAnlZ_bP0GMRy2NTVLqU4tNZQ-J_hYS_xg",
    authDomain: "ticket-0.firebaseapp.com",
    databaseURL: "https://ticket-0.firebaseio.com",
    projectId: "ticket-0",
    storageBucket: "ticket-0.appspot.com",
    messagingSenderId: "679047401067",
    appId: "1:679047401067:web:facbcf80e4258053bbab2e",
    measurementId: "G-P0YSC4JWFF"
};

firebase.initializeApp(firebaseConfig);
window.firebase = firebase;