<!DOCTYPE html>
<html lang="ru">

<head>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="АвтоСервис">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'МАКК') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Фирменные стили МАКК -->
    <style>
        :root {
            --makk-yellow: #feed01;
            --makk-dark: #2e2d2d;
            --makk-black: #000000;
            --makk-gray: #898989;
            --makk-light: #fbfcf9;
            --makk-border: #e1e1df;
            --makk-shadow: 0px 2px 6px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Open Sans', system-ui, -apple-system, sans-serif;
            background: var(--makk-light);
            color: var(--makk-black);
        }

        /* Навигация */
        .navbar-makk {
            background: var(--makk-yellow);
            box-shadow: var(--makk-shadow);
            padding: 0.75rem 1rem;
        }

        .navbar-makk .navbar-brand {
            color: var(--makk-black);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .navbar-makk .nav-link {
            color: var(--makk-black);
            font-weight: 500;
            margin: 0 0.5rem;
        }

        .navbar-makk .nav-link:hover,
        .navbar-makk .nav-link.active {
            color: var(--makk-dark);
        }

        /* Боковая панель */
        .sidebar-makk {
            background: #fff;
            border-right: 1px solid var(--makk-border);
            min-height: calc(100vh - 56px);
        }

        .sidebar-makk .nav-link {
            color: var(--makk-black);
            padding: 0.6rem 1rem;
            border-radius: 2px;
            margin-bottom: 4px;
            font-weight: 400;
        }

        .sidebar-makk .nav-link:hover,
        .sidebar-makk .nav-link.active {
            background: rgba(238, 242, 244, 0.6);
            color: var(--makk-dark);
            font-weight: 600;
        }

        /* Карточки и таблицы */
        .card-makk {
            background: #fff;
            border: 1px solid var(--makk-border);
            border-radius: 2px;
            box-shadow: none;
        }

        .card-makk .card-header {
            background: #fff;
            border-bottom: 1px solid var(--makk-border);
            font-weight: 600;
            color: var(--makk-dark);
        }

        .table-makk {
            background: #fff;
        }

        .table-makk thead {
            background: var(--makk-light);
        }

        .table-makk th {
            font-weight: 600;
            color: var(--makk-dark);
            border-bottom: 1px solid var(--makk-border);
        }

        /* Кнопки */
        .btn-makk {
            background: var(--makk-dark);
            color: #fff;
            border: none;
            border-radius: 2px;
            font-weight: 500;
            padding: 0.375rem 1rem;
        }

        .btn-makk:hover {
            background: #1a1a1a;
            color: #fff;
        }

        .btn-outline-makk {
            background: transparent;
            color: var(--makk-dark);
            border: 1px solid var(--makk-dark);
            border-radius: 2px;
        }

        .btn-outline-makk:hover {
            background: var(--makk-dark);
            color: #fff;
        }

        /* Формы */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--makk-yellow);
            box-shadow: 0 0 0 0.2rem rgba(254, 237, 1, 0.25);
        }

        /* Статусы */
        .badge-makk {
            border-radius: 2px;
            font-weight: 500;
            padding: 0.35em 0.6em;
        }

        .badge-makk-success {
            background: #28a745;
            color: #fff;
        }

        .badge-makk-warning {
            background: #ffc107;
            color: #000;
        }

        .badge-makk-danger {
            background: #dc3545;
            color: #fff;
        }

        .badge-makk-secondary {
            background: var(--makk-gray);
            color: #fff;
        }

        /* Логотип */
        .navbar-logo {
            height: 32px;
            width: auto;
            margin-right: 0.5rem;
        }
    </style>

    <!-- Шрифт Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body>
    @auth
        <!-- Верхняя панель для авторизованных -->
        <nav class="navbar navbar-expand-lg navbar-makk sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                    <!-- Логотип МАКК -->
                    <img src="{{ asset('images/makk-logo.png') }}" alt="МАКК" class="navbar-logo">
                    <span>Личный кабинет: Расходы и напоминания</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <span class="text-muted small me-3">{{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-makk btn-sm">Выйти</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <!-- Боковое меню -->
                <aside class="col-md-3 col-lg-2 d-none d-md-block sidebar-makk p-3">
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
        <nav class="navbar navbar-makk">
            <div class="container">
                <a class="navbar-brand" href="/">МАКК</a>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-makk btn-sm">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-makk btn-sm">Регистрация</a>
                </div>
            </div>
        </nav>
        <main class="container py-5">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js')
        .then(reg => console.log('SW registered'))
        .catch(err => console.warn('SW error', err));
    });
  }
</script>
</body>

</html>