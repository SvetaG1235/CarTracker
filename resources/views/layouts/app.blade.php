<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CarTracker') }}</title>
        <!-- PWA -->
<link rel="manifest" href="https://cartracker-aldh.onrender.com/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="CarTracker">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Твои стили (принудительно HTTPS) -->
    <link href="https://cartracker-aldh.onrender.com/css/auto-style.css" rel="stylesheet">

    <!-- Шрифт Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body>
    @auth
        <!-- Верхняя панель для авторизованных -->
       <nav class="navbar navbar-app sticky-top">
    <div class="container-fluid">
<a class="navbar-brand" href="{{ route('dashboard') }}">
    <img src="https://cartracker-aldh.onrender.com/images/logo.png" alt="Cartracker" class="navbar-logo">
    <div class="brand-content">
        <span class="brand-title">CarTracker</span>
        <span class="brand-subtitle">Расходы и напоминания</span>
    </div>
</a>
        
        <!-- Кнопка бургер (видна ТОЛЬКО на мобильных) -->
        <button class="navbar-toggler d-md-none" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" 
                aria-controls="navbarNav" 
                aria-expanded="false" 
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Выпадающее меню (ТОЛЬКО для мобильных) -->
        <div class="collapse navbar-collapse d-md-none" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cars.*') ? 'active' : '' }}" 
                       href="{{ route('cars.index') }}">Автомобили</a>
                </li>
                <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('photos.*') ? 'active' : '' }}" 
       href="{{ route('photos.index') }}">📸 Галерея</a>
</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" 
                       href="{{ route('expenses.index') }}">Расходы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}" 
                       href="{{ route('reminders.index') }}">Напоминания</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}" 
                       href="{{ route('reports') }}">Отчёты</a>
                </li>
            </ul>
            
            <!-- Имя и выход -->
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <span class="text-white-50 small me-3">{{ Auth::user()->name }}</span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Выйти</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

        <div class="container-fluid">
            <div class="row">
                <!-- Боковое меню -->
                <aside class="col-md-3 col-lg-2 d-none d-md-block sidebar-app p-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                Главная
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cars.index') }}"
                                class="nav-link {{ request()->routeIs('cars.*') ? 'active' : '' }}">
                                Автомобили
                            </a>
                        </li>
                        <li class="nav-item">
    <a href="{{ route('photos.index') }}"
       class="nav-link {{ request()->routeIs('photos.*') ? 'active' : '' }}">
        📸 Галерея
    </a>
</li>
                        <li class="nav-item">
                            <a href="{{ route('expenses.index') }}"
                                class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                                Расходы
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reminders.index') }}"
                                class="nav-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}">
                                Напоминания
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports') }}"
                                class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                                Отчёты
                            </a>
                        </li>
                        <li class="nav-item mt-3 pt-3 border-top">
                            <a href="{{ route('profile.edit') }}" class="nav-link">
                                Назад
                            </a>
                        </li>
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item mt-2">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link text-danger">
                                    Администрирование
                                </a>
                            </li>
                        @endif
                    </ul>
                </aside>

                <!-- Основной контент -->
                <main class="col-md-9 col-lg-10 p-3 p-md-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <!-- Для неавторизованных (если нужна публичная страница) -->
        <nav class="navbar navbar-app">
            <div class="container">
<a class="navbar-brand" href="/">
    <img src="https://cartracker-aldh.onrender.com/images/logo.png" alt="Cartracker" class="navbar-logo">
    CarTracker
</a>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-app btn-sm">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-app btn-sm">Регистрация</a>
                </div>
            </div>
        </nav>
        <main class="container py-5">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    @endauth

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <script>
  // Регистрация Service Worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
navigator.serviceWorker.register('https://cartracker-aldh.onrender.com/sw.js')
        .then(reg => console.log('SW registered:', reg))
        .catch(err => console.warn('SW error:', err));
    });
  }
</script>
</body>

</html>