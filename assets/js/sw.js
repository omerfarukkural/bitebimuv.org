/**
 * BBM Service Worker – PWA Çevrimdışı Destek
 * Bite Bi Muv Derneği Teması v4.0
 */
'use strict';

const BBM_CACHE_VERSION = 'bbm-v4';
const BBM_STATIC_CACHE  = `${BBM_CACHE_VERSION}-static`;
const BBM_DYNAMIC_CACHE = `${BBM_CACHE_VERSION}-dynamic`;
const BBM_IMG_CACHE     = `${BBM_CACHE_VERSION}-images`;

const OFFLINE_URL = '/offline/';

// Assets to pre-cache on install
const PRECACHE_URLS = [
    '/',
    OFFLINE_URL,
];

// Max items in dynamic cache
const MAX_DYNAMIC_ITEMS = 50;
const MAX_IMG_ITEMS     = 30;

// ── Install ───────────────────────────────────────────────────────────────────

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(BBM_STATIC_CACHE)
            .then(cache => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting())
    );
});

// ── Activate ──────────────────────────────────────────────────────────────────

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys
                    .filter(k => k.startsWith('bbm-') && !k.startsWith(BBM_CACHE_VERSION))
                    .map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ── Fetch Strategy ────────────────────────────────────────────────────────────

self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET, admin, API, analytics
    if (request.method !== 'GET') return;
    if (url.pathname.startsWith('/wp-admin')) return;
    if (url.pathname.startsWith('/wp-login')) return;
    if (url.pathname.includes('wp-json')) return;
    if (url.hostname.includes('google-analytics')) return;
    if (url.hostname.includes('googletagmanager')) return;
    if (url.searchParams.has('preview')) return;

    // Images: cache-first with size limit
    if (request.destination === 'image') {
        event.respondWith(cacheFirst(request, BBM_IMG_CACHE, MAX_IMG_ITEMS));
        return;
    }

    // Static assets (CSS/JS fonts): cache-first
    if (
        url.pathname.match(/\.(css|js|woff2?|ttf|eot)(\?.*)?$/) ||
        url.hostname !== self.location.hostname
    ) {
        event.respondWith(cacheFirst(request, BBM_STATIC_CACHE, MAX_DYNAMIC_ITEMS));
        return;
    }

    // HTML pages: network-first with offline fallback
    if (request.destination === 'document') {
        event.respondWith(networkFirst(request));
        return;
    }
});

// ── Strategies ────────────────────────────────────────────────────────────────

async function cacheFirst(request, cacheName, maxItems) {
    const cache    = await caches.open(cacheName);
    const cached   = await cache.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            await cache.put(request, response.clone());
            await trimCache(cache, maxItems);
        }
        return response;
    } catch {
        return new Response('', { status: 408, statusText: 'Offline' });
    }
}

async function networkFirst(request) {
    const cache = await caches.open(BBM_DYNAMIC_CACHE);

    try {
        const response = await fetch(request);
        if (response.ok) {
            await cache.put(request, response.clone());
            await trimCache(cache, MAX_DYNAMIC_ITEMS);
        }
        return response;
    } catch {
        const cached = await cache.match(request);
        if (cached) return cached;

        // Return offline page for document requests
        const offlinePage = await caches.match(OFFLINE_URL);
        return offlinePage || new Response(
            '<h1>Çevrimdışı</h1><p>İnternet bağlantısı yok.</p>',
            { status: 503, headers: { 'Content-Type': 'text/html; charset=utf-8' } }
        );
    }
}

// ── Utilities ─────────────────────────────────────────────────────────────────

async function trimCache(cache, maxItems) {
    const keys = await cache.keys();
    if (keys.length > maxItems) {
        await cache.delete(keys[0]);
    }
}
