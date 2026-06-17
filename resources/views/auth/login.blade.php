<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1">Вход</h4>
      
    </div>
    <!-- Session Status -->
    @if(session('status'))
        <div class="alert alert-success mb-4" role="alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Электронная почта</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" 
                   required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>



        <button type="submit" class="btn btn-primary w-100 mb-3">
            Войти
        </button>

        <div class="text-center small">
            Ещё нет аккаунта? 
            <a href="{{ route('register') }}" class="text-decoration-none">Зарегистрироваться</a>
        </div>
    </form>
</x-guest-layout>