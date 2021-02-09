import firebase from "firebase/app";
import "firebase/auth";

const firebaseConfig = {
    apiKey: process.env.MIX_GOOGLE_API_TOKEN,
    authDomain: "ticket-0.firebaseapp.com",
    databaseURL: "https://ticket-0.firebaseio.com",
    projectId: "ticket-0",
    storageBucket: "ticket-0.appspot.com",
    messagingSenderId: "679047401067",
    appId: "1:679047401067:web:facbcf80e4258053bbab2e",
    measurementId: "G-P0YSC4JWFF"
};

firebase.initializeApp(firebaseConfig);

if(window.firebase == null)
    window.firebase = firebase;