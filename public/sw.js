'use strict';

const CACHE_NAME = 'aimpro-care-v1';
const OFFLINE_URL = '/offline';

const PRECACHE_URLS = [
    '/',
    '/login',
    '/cek-garansi',
    '/assets/css/aimpro.css',
    '/assets/js/app.js',
    '/assets/icons/icon-192x192.png',
    '/assets/icons/icon-512x512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_URLS);
        })
    );

    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );

    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    if (! request.url.startsWith('http')) {
        return;
    }

    if (request.url.includes('/admin/') || request.url.includes('/qc/')) {
        event.respondWith(
            fetch(request).catch(() => {
                return caches.match(OFFLINE_URL);
            })
        );
        return;
    }

    event.respondWith(
        caches.match(request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(request).then((response) => {
                if (
                    response &&
                    response.status === 200 &&
                    response.type === 'basic'
                ) {
                    const responseClone = response.clone();

                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                }

                return response;
            }).catch(() => {
                if (request.headers.get('accept').includes('text/html')) {
                    return caches.match(OFFLINE_URL);
                }
            });
        })
    );
});