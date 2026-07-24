const CACHE_NAME = 'skb-cache-v1';
const ASSETS_TO_CACHE = [
    '/manifest.json',
    'https://unpkg.com/@tailwindcss/browser@4',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
    'https://cdn.jsdelivr.net/npm/idb@8/build/umd.js'
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Opened cache');
                return cache.addAll(ASSETS_TO_CACHE);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate Event - clean up old caches
self.addEventListener('activate', (event) => {
    const cacheAllowlist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheAllowlist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event - Stale While Revalidate / Cache First strategy
self.addEventListener('fetch', (event) => {
    // Only cache GET requests
    if (event.request.method !== 'GET') return;

    // Do not cache API requests or anything that shouldn't be cached
    // We mainly want to cache static assets
    
    event.respondWith(
        caches.match(event.request)
            .then((cachedResponse) => {
                // Return cached response immediately if found
                if (cachedResponse) {
                    // Fetch in background to update cache (Stale While Revalidate)
                    fetch(event.request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                            const responseToCache = networkResponse.clone();
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(event.request, responseToCache);
                            });
                        }
                    }).catch(() => {
                        // Ignore background fetch errors (we're offline)
                    });
                    
                    return cachedResponse;
                }

                // If not in cache, fetch from network
                return fetch(event.request).then((networkResponse) => {
                    // Don't cache non-successful responses
                    if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                        return networkResponse;
                    }

                    const responseToCache = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });

                    return networkResponse;
                }).catch(() => {
                    // Optional: Return a custom offline page if HTML is requested and we are offline
                });
            })
    );
});
