<x-guest-layout>
    <!-- Заголовок страницы -->
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1">Регистрация</h4>
        <p class="text-muted small">Создайте аккаунт для доступа к CarTracker</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name') }}" 
                   required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Электронная почта</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" 
                   required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                   id="password_confirmation" name="password_confirmation" 
                   required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-app w-100 mb-3">
            Зарегистрироваться
        </button>

        <div class="text-center small">
            Уже зарегистрированы? 
            <a href="{{ route('login') }}" class="text-decoration-none">Войти</a>
        </div>
    </form>
</x-guest-layout>