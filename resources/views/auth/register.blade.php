<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="h3 fw-bold text-dark mb-2">Create Account</h2>
        <p class="text-muted mb-0">Join us and start your bakery journey</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fas fa-user me-2 text-warning"></i>Full Name
            </label>
            <input id="name" 
                   class="form-control" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name" 
                   placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
                   autocomplete="new-password" 
                   placeholder="Create a strong password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-2 text-warning"></i>Confirm Password
            </label>
            <input id="password_confirmation" 
                   class="form-control" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password" 
                   placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn btn-primary w-100 mb-4">
            <i class="fas fa-user-plus me-2"></i>Create Account
        </button>

        <!-- Divider -->
        <div class="position-relative mb-4">
            <div class="border-top"></div>
            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                <span class="text-muted small">Already have an account?</span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" 
               class="btn btn-outline-primary w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
