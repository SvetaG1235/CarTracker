{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Авторизация') }} — {{ config('app.name', 'CarTracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .auth-card {
            width: 100%;
            max-width: 480px;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border: none;
        }
        .auth-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .auth-brand:hover { color: #764ba2; }
    </style>
</head>
<body class="auth-bg">
    <div class="card auth-card p-4 p-md-5">
        <div class="text-center">
            <a href="/" class="auth-brand">🚗 {{ config('app.name', 'CarTracker') }}</a>
        </div>
        
        {{ $slot }}
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>