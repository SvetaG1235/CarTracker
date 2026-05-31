const CACHE_NAME = 'cartracker-v2'; // ← Увеличил версию, чтобы сбросить старый кэш

// Кэшируем ТОЛЬКО статику (не страницы!)
const STATIC_ASSETS = [
  '/css/auto-style.css',
  '/images/logo.png',
  '/images/icon-192.png',
  '/images/icon-512.png',
  '/manifest.json',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
  'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(STATIC_ASSETS))
      .catch(err => console.warn('Cache init failed:', err))
  );
});

self.addEventListener('activate', event => {
  // Удаляем старые кэши с другим именем
  event.waitUntil(
    caches.keys().then(names => 
      Promise.all(
        names.filter(name => name !== CACHE_NAME)
             .map(name => caches.delete(name))
      )
    )
  );
});

// Стратегия: "Сначала сеть, потом кэш" (для HTML), "Сначала кэш" (для статики)
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // HTML-страницы: всегда идём на сервер, кэш — только если офлайн
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .catch(() => caches.match(request))
    );
    return;
  }

  // Статика: сначала кэш, потом сеть
  event.respondWith(
    caches.match(request)
      .then(cached => cached || fetch(request))
  );
});