const CACHE_NAME = 'cartracker-v5'; // ← Новая версия

// Кэшируем ВСЕ необходимые страницы + статику
const STATIC_ASSETS = [
  '/',
  '/login',           // ← Добавляем страницу входа
  '/register',        // ← Добавляем страницу регистрации
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
  const url = new URL(request.url);

  // HTML-страницы (навигация)
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then(response => {
          // Если успех — возвращаем ответ
          return response;
        })
        .catch(() => {
          // Если сеть недоступна — ищем в кэше
          return caches.match(request)
            .then(cached => {
              // Если нашли в кэше — возвращаем
              if (cached) return cached;
              // Иначе пробуем главную или login
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