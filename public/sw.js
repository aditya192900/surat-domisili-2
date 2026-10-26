// public/sw.js
const CACHE_NAME = 'surat-kelurahan-v1';
const urlsToCache = [
  '/',
  '/manifest-warga.json',
  '/manifest-rt.json',
  '/manifest-kelurahan.json',
  '/images/icon-warga.png',
  '/images/icon-rt.png',
  '/images/icon-kelurahan.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) return response;
        return fetch(event.request);
      })
  );
});