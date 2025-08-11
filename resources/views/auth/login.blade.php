<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="h3 fw-bold text-dark mb-2">Welcome Back!</h2>
        <p class="text-muted mb-0">Sign in to your account to continue</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2 text-warning"></i>Email Address
            </label>
            <input id="email" 
                   class="form-control" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username" 
                   placeholder="Enter your email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2 text-warning"></i>Password
            </label>
            <input id="password" 
                   class="form-control" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password" 
                   placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" 
                       type="checkbox" 
                       class="form-check-input" 
                       name="remember">
                <label class="form-check-label text-muted" for="remember_me">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-none text-warning fw-medium" 
                   href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn btn-primary w-100 mb-4">
            <i class="fas fa-sign-in-alt me-2"></i>Sign In
        </button>

        <!-- Divider -->
        <div class="position-relative mb-4">
            <div class="border-top"></div>
            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                <span class="text-muted small">New to {{ config('app.name', 'Bakery') }}?</span>
            </div>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center">
                <a href="{{ route('register') }}" 
                   class="btn btn-outline-primary w-100">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
