const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableSuccessNotifications();

mix.js('resources/js/app.js', 'public/js')
    //.extract(['alpinejs', 'laravel-echo', 'pikaday-time', 'pusher-js', 'intro.js'])

    .js('resources/js/turbo.js', 'public/js')

    .js('resources/js/reservation.js', 'public/js')

    .js('resources/js/firebase.js', 'public/js')

    .js('resources/js/print.js', 'public/js')

    .js('resources/js/service-worker.js', 'public')

    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ])
    .sass('resources/css/reservation.scss', 'public/css');

    //.version();
