{{-- resources/views/auth/confirm-password.blade.php --}}
<x-guest-layout>
    <p class="text-muted mb-4 small">
        {{ __('This is a secure area. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">
            {{ __('Confirm') }}
        </button>
    </form>
</x-guest-layout>