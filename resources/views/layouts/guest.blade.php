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
        :root {
            /* Те же переменные, что в auto-style.css */
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-800: #1e40af;
            --gradient-primary: linear-gradient(135deg, var(--primary-600), var(--primary-800));
            --bg-main: #f1f5f9;
            --gray-600: #475569;
            --gray-700: #334155;
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 10px 10px -5px rgb(0 0 0 / 0.04);
            --radius-lg: 12px;
        }
        
        .auth-bg {
            background: var(--gradient-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .auth-card {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border: none;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .auth-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-700);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .auth-brand:hover { 
            color: var(--primary-800); 
        }
        
        /* Стили для форм (совместимость с Bootstrap + твой дизайн) */
        .auth-card .form-label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.9rem;
        }
        
        .auth-card .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }
        
        .auth-card .form-control:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            outline: none;
        }
        
        .auth-card .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
        }
        
        .auth-card .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-700), var(--primary-900));
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.4);
        }
        
        .auth-card .text-muted {
            color: var(--gray-600) !important;
        }
        
        .auth-card a {
            color: var(--primary-600);
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-card a:hover {
            color: var(--primary-800);
            text-decoration: underline;
        }
    </style>
</head>
<body class="auth-bg">
    <div class="card auth-card p-4 p-md-5">
        <div class="text-center">
            <a href="/" class="auth-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Cartracker" style="height: 36px;"> 
                 {{ config('app.name', 'CarTracker') }}
            </a>
        </div>
        
        {{ $slot }}
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>