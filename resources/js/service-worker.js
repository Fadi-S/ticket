let CACHE_NAME = 'ticket-cache-v2';
let urlsToCache = [
    '/js/app.js',
    '/css/app.css',
    '/js/turbo.js',
    '/images/jesus-500.jpg',
    '/images/stgeorge_bg-500.jpg',
    '/images/defaultPicture.png',
    '/images/stg_logo-250.png',
];

self.addEventListener('install', event => {
    // Perform install steps
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('opened cache');
            return cache.addAll(urlsToCache);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});

self.addEventListener('activate', event => {

    let cacheAllowlist = [CACHE_NAME];

    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheAllowlist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});