const CACHE_NAME = 'cartracker-v3'; // ← Новая версия для сброса кэша

// Кэшируем только статику + главную страницу как fallback
const STATIC_ASSETS = [
  '/',  // ← Главная страница (fallback для офлайна)
  '/css/auto-style.css',
  '/images/logo.png',
  '/images/icon-192.png',
  '/images/icon-512.png',
  '/manifest.json'
  // CDN-ресурсы лучше убрать: они могут не закэшироваться из-за CORS
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(STATIC_ASSETS))
      .catch(err => console.warn('Cache init failed:', err))
  );
  self.skipWaiting(); // ← Активировать новый SW сразу
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
  self.clients.claim(); // ← Взять контроль над страницами сразу
});

self.addEventListener('fetch', event => {
  const { request } = event;

  // HTML-страницы: сеть → fallback на главную
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .catch(() => caches.match('/'))  // ← Fallback на закэшированную главную
    );
    return;
  }

  // Статика: кэш → сеть
  event.respondWith(
    caches.match(request)
      .then(cached => cached || fetch(request))
  );
});