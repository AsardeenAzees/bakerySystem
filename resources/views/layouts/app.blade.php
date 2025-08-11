<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bakery') }}</title>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite (Bootstrap + app.js) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
<div id="app">
    @php
        // Cart item count (session-based cart from CartService)
        $cartCount = collect(session('cart', []))->sum('qty');
    @endphp

    <!-- Enhanced Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="fas fa-birthday-cake text-primary me-2 fs-3"></i>
                <span class="fw-bold fs-4">{{ config('app.name', 'Bakery') }}</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side -->
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->hasAnyRole(['customer','admin']))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('shop.index') }}">
                                    <i class="fas fa-store me-1"></i> Shop
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center position-relative" href="{{ route('cart.index') }}">
                                    <i class="fas fa-shopping-cart me-1"></i> Cart
                                    @if($cartCount > 0)
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('addresses.index') }}">
                                    <i class="fas fa-map-marker-alt me-1"></i> Addresses
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.orders.index') }}">
                                    <i class="fas fa-clipboard-list me-1"></i> Orders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('admin.customers.index') }}">
                                    <i class="fas fa-users me-1"></i> Customers
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isChef() || auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('chef.tasks') }}">
                                    <i class="fas fa-utensils me-1"></i> Chef
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isDelivery() || auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('delivery.list') }}">
                                    <i class="fas fa-truck me-1"></i> Delivery
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Right Side -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-primary btn-sm me-2" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary btn-sm" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> {{ __('Register') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                                <span class="badge bg-secondary ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                @if(auth()->user()->hasAnyRole(['customer','admin']))
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('shop.index') }}">
                                        <i class="fas fa-store me-2"></i> Shop
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('cart.index') }}">
                                        <i class="fas fa-shopping-cart me-2"></i> Cart
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Admin
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.customers.index') }}">
                                        <i class="fas fa-users me-2"></i> Customers
                                    </a>
                                @endif

                                @if(auth()->user()->isChef() || auth()->user()->isAdmin())
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('chef.tasks') }}">
                                        <i class="fas fa-utensils me-2"></i> Chef
                                    </a>
                                @endif

                                @if(auth()->user()->isDelivery() || auth()->user()->isAdmin())
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('delivery.list') }}">
                                        <i class="fas fa-truck me-2"></i> Delivery
                                    </a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Enhanced Global alerts -->
    <div class="container mt-3">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Please check the form:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @include('partials.flash')
    </div>

    <main class="py-4">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
