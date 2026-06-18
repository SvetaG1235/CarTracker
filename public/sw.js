const CACHE_NAME = 'cartracker-v7-final'; 

// Кэшируем только те страницы, которые точно нужны офлайн
const PRECACHE_URLS = [
  '/',                // Главная (Дашборд)
  '/login',           // Страница входа
  '/register',        // Страница регистрации
  '/css/auto-style.css',
  '/images/logo.png',
  '/manifest.json',
  '/js/main.js'       // Если используешь свой JS, укажи путь
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_URLS);
    }).catch(err => console.warn('Cache init issue:', err));
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(keyList.map((key) => {
        if (key !== CACHE_NAME) {
          return caches.delete(key);
        }
      }));
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  // Игнорируем запросы не от нашего сайта
  if (!event.request.url.startsWith(self.location.origin)) return;

  // Логика для HTML страниц (Навигация)
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .catch(() => {
          // Сеть недоступна (офлайн)! Ищем в кэше
          return caches.match(event.request)
            .then((cached) => {
              if (cached) return cached; 
              // Если конкретной страницы нет в кэше — показываем главную
              return caches.match('/'); 
            });
        })
    );
    return;
  }

  // Логика для статики (картинки, CSS, шрифты)
  // Сначала кэш, потом сеть (если сеть есть)
  event.respondWith(
    caches.match(event.request).then((cached) => {
      return cached || fetch(event.request).catch(() => {}); // Ошибку статички глушим
    })
  );
});