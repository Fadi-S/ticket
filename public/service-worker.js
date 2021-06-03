(self["webpackChunk"] = self["webpackChunk"] || []).push([["/service-worker"],{

/***/ "./resources/js/service-worker.js":
/*!****************************************!*\
  !*** ./resources/js/service-worker.js ***!
  \****************************************/
/***/ (() => {

var CACHE_NAME = 'ticket-cache-v2';
var urlsToCache = ['/js/app.js', '/css/app.css', '/js/turbo.js', '/images/jesus-500.jpg', '/images/stgeorge_bg-500.jpg', '/images/defaultPicture.png', '/images/stg_logo-250.png'];
self.addEventListener('install', function (event) {
  // Perform install steps
  event.waitUntil(caches.open(CACHE_NAME).then(function (cache) {
    console.log('opened cache');
    return cache.addAll(urlsToCache);
  }));
});
self.addEventListener('fetch', function (event) {
  event.respondWith(fetch(event.request)["catch"](function () {
    return caches.match(event.request);
  }));
});
self.addEventListener('activate', function (event) {
  var cacheAllowlist = [CACHE_NAME];
  event.waitUntil(caches.keys().then(function (cacheNames) {
    return Promise.all(cacheNames.map(function (cacheName) {
      if (cacheAllowlist.indexOf(cacheName) === -1) {
        return caches["delete"](cacheName);
      }
    }));
  }));
});

/***/ })

},
0,[["./resources/js/service-worker.js","/manifest"]]]);