const CACHE_NAME = 'cartracker-v6'; // ← Новая версия

// Кэшируем публичные страницы + статику
const STATIC_ASSETS = [
  '/',
  '/login',
  '/register',
  '/css/auto-style.css',
  '/images/logo.png',
  '/images/icon-192.png',
  '/images/icon-512.png',
  '/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(STATIC_ASSETS))
      .catch(err => console.warn('Cache init failed:', err))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(names => 
      Promise.all(
        names.filter(name => name !== CACHE_NAME)
             .map(name => caches.delete(name))
      )
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  const { request } = event;

  // HTML-страницы (навигация)
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request, { redirect: 'follow' })  // ← Явно следуем за редиректами
        .then(response => {
          // Если успех и это HTML — кэшируем
          if (response.ok && response.headers.get('content-type').includes('text/html')) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, responseClone);
            });
          }
          return response;
        })
        .catch(() => {
          // Если сеть недоступна — ищем в кэше
          return caches.match(request)
            .then(cached => {
              if (cached) return cached;
              // Если нет точного совпадения — пробуем главную или login
              return caches.match('/')
                .then(main => main || caches.match('/login'));
            });
        })
    );
    return;
  }

  // Статика: кэш → сеть
  event.respondWith(
    caches.match(request)
      .then(cached => cached || fetch(request))
  );
});