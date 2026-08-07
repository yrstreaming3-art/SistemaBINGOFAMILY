/**
 * sw.js - Service Worker de Bingo SaaS
 * Ruta: public/sw.js
 *
 * Requisito indispensable para que el navegador ofrezca instalar
 * el sitio como PWA ("Agregar a pantalla de inicio").
 *
 * Cachea unicamente archivos estaticos propios (CSS, JS, iconos).
 * Las paginas dinamicas (login, dashboard, etc.) siempre se piden
 * a la red para no mostrar datos desactualizados o de otro usuario.
 */

const CACHE_NAME = 'bingo-saas-cache-v1';

const ASSETS_TO_CACHE = [
    'assets/css/style.css',
    'assets/js/main.js',
    'assets/img/bg-pattern.svg',
    'assets/img/icons/icon-192x192.png',
    'assets/img/icons/icon-512x512.png',
    'manifest.json',
];

// Instalacion: precachea los assets estaticos
self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

// Activacion: elimina caches de versiones anteriores
self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

// Estrategia: solo intercepta peticiones GET a los assets estaticos propios.
// Todo lo demas (HTML dinamico, POST, dominios externos como CDN) va directo a la red.
self.addEventListener('fetch', function (event) {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);
    const isSameOrigin = url.origin === self.location.origin;
    const isStaticAsset = /\.(css|js|svg|png|jpg|jpeg|webp|ico|json)$/.test(url.pathname);

    if (!isSameOrigin || !isStaticAsset) {
        return; // deja pasar la peticion normalmente (red)
    }

    event.respondWith(
        caches.match(request).then(function (cachedResponse) {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(request).then(function (networkResponse) {
                return caches.open(CACHE_NAME).then(function (cache) {
                    cache.put(request, networkResponse.clone());
                    return networkResponse;
                });
            });
        }).catch(function () {
            // Sin red y sin cache: no hay nada mas que ofrecer para este asset
            return new Response('', { status: 504, statusText: 'Sin conexion' });
        })
    );
});
