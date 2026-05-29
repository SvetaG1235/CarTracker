{{-- resources/views/auth/forgot-password.blade.php --}}
<x-guest-layout>
    <p class="text-muted mb-4 small">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
    </p>

    @if(session('status'))
        <div class="alert alert-success mb-4" role="alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" 
                   required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">
            {{ __('Email Password Reset Link') }}
        </button>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small">
                {{ __('Back to login') }}
            </a>
        </div>
    </form>
</x-guest-layout>