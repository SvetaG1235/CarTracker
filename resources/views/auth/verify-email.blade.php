{{-- resources/views/auth/verify-email.blade.php --}}
<x-guest-layout>
    <p class="text-muted mb-4 small">
        {{ __('Thanks for signing up! Please verify your email address by clicking the link we sent you.') }}
    </p>

    @if(session('status') === 'verification-link-sent')
        <div class="alert alert-success mb-4" role="alert">
            {{ __('A new verification link has been sent to your email.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-primary w-100">
            {{ __('Resend Verification Email') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 text-decoration-none small">
            {{ __('Log Out') }}
        </button>
    </form>
</x-guest-layout>