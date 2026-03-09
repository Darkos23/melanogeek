const CACHE_NAME = 'melanogeek-v1';

// Ressources essentielles à pré-cacher
const PRECACHE_URLS = [
    '/',
    '/offline',
];

// ── Install : pré-cache des ressources statiques ──
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(PRECACHE_URLS)).then(() => self.skipWaiting())
    );
});

// ── Activate : nettoyage des anciens caches ──
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

// ── Fetch : stratégie Network First pour les pages, Cache First pour les assets ──
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignore les requêtes non-GET et hors domaine
    if (request.method !== 'GET' || !url.origin.includes(self.location.origin.replace(/https?:\/\//, ''))) {
        return;
    }

    // Assets statiques (CSS, JS, images) → Cache First
    if (url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|woff2?|ico)$/)) {
        event.respondWith(
            caches.match(request).then(cached => cached || fetch(request).then(response => {
                const clone = response.clone();
                caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                return response;
            }))
        );
        return;
    }

    // Pages HTML → Network First, fallback cache puis offline
    event.respondWith(
        fetch(request)
            .then(response => {
                // Met en cache les pages réussies
                if (response.ok) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                }
                return response;
            })
            .catch(() => caches.match(request).then(cached => cached || caches.match('/offline')))
    );
});
