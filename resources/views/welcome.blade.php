<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bakery') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                --primary-color: #8B4513;
                --primary-dark: #654321;
                --secondary-color: #FFD700;
                --accent-color: #FF6B35;
            }
            
            .hero-section {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                position: relative;
                overflow: hidden;
            }
            
            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                opacity: 0.3;
            }
            
            .feature-card {
                background: white;
                border-radius: 1rem;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }
            
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                border-color: var(--secondary-color);
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                font-size: 2rem;
                color: white;
            }
            
            .btn-custom {
                padding: 0.75rem 2rem;
                border-radius: 2rem;
                font-weight: 600;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.3s ease;
                border: none;
            }
            
            .btn-primary-custom {
                background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
                color: white;
            }
            
            .btn-primary-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
                color: white;
            }
            
            .btn-outline-custom {
                border: 2px solid white;
                color: white;
                background: transparent;
            }
            
            .btn-outline-custom:hover {
                background: white;
                color: var(--primary-color);
                transform: translateY(-2px);
            }
            
            .floating-shapes {
                position: absolute;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 1;
            }
            
            .shape {
                position: absolute;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                animation: float 6s ease-in-out infinite;
            }
            
            .shape:nth-child(1) {
                width: 80px;
                height: 80px;
                top: 20%;
                left: 10%;
                animation-delay: 0s;
            }
            
            .shape:nth-child(2) {
                width: 120px;
                height: 120px;
                top: 60%;
                right: 10%;
                animation-delay: 2s;
            }
            
            .shape:nth-child(3) {
                width: 60px;
                height: 60px;
                bottom: 20%;
                left: 20%;
                animation-delay: 4s;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(180deg); }
            }
            
            .navbar-custom {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="bg-light">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="fas fa-birthday-cake text-warning me-2 fs-3"></i>
                    <span class="fw-bold fs-4 text-dark">{{ config('app.name', 'Bakery') }}</span>
                </a>
                
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-outline-primary btn-sm me-2" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> Sign In
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary btn-sm" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i> Register
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary btn-sm" href="{{ url('/dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section text-white py-5" style="padding-top: 120px !important;">
            <div class="floating-shapes">
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
            </div>
            
            <div class="container position-relative" style="z-index: 2;">
                <div class="row align-items-center min-vh-75">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="display-4 fw-bold mb-4">
                            Fresh Baked <span class="text-warning">Goodness</span><br>
                            Delivered to Your Door
                        </h1>
                        <p class="lead mb-5 text-white-50">
                            Discover our delicious selection of fresh baked goods made with love and the finest ingredients. 
                            Every bite tells a story of tradition and quality.
                        </p>
                        
                        @guest
                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                                <a href="{{ route('register') }}" class="btn-custom btn-primary-custom">
                                    <i class="fas fa-rocket"></i> Start Your Order
                                </a>
                                <a href="{{ route('login') }}" class="btn-custom btn-outline-custom">
                                    <i class="fas fa-sign-in-alt"></i> Sign In
                                </a>
                            </div>
                        @else
                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                                <a href="{{ route('shop.index') }}" class="btn-custom btn-primary-custom">
                                    <i class="fas fa-shopping-cart"></i> Start Shopping
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn-custom btn-outline-custom">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </div>
                        @endguest
                    </div>
                    
                    <div class="col-lg-6 text-center mt-5 mt-lg-0">
                        <div class="position-relative">
                            <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 300px; height: 300px;">
                                <i class="fas fa-birthday-cake text-white" style="font-size: 8rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5 bg-white">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-bold text-dark mb-3">Why Choose Our Bakery?</h2>
                    <p class="lead text-muted">We're committed to delivering the best experience</p>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card text-center h-100">
                            <div class="feature-icon">
                                <i class="fas fa-bread-slice"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Fresh Daily</h4>
                            <p class="text-muted mb-0">
                                All our products are baked fresh every morning using traditional recipes passed down through generations.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card text-center h-100">
                            <div class="feature-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Fast Delivery</h4>
                            <p class="text-muted mb-0">
                                Quick and reliable delivery to your doorstep with real-time tracking. Hot and fresh, just like you like it.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card text-center h-100">
                            <div class="feature-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Quality Assured</h4>
                            <p class="text-muted mb-0">
                                Premium ingredients and expert bakers ensure the best taste and quality in every product we make.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        @guest
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="display-5 fw-bold text-dark mb-4">Ready to Taste the Difference?</h2>
                        <p class="lead text-muted mb-5">
                            Join thousands of happy customers who love our fresh baked goods. 
                            Create your account today and start enjoying the best bakery experience.
                        </p>
                        <a href="{{ route('register') }}" class="btn-custom btn-primary-custom">
                            <i class="fas fa-user-plus"></i> Create Your Account
                        </a>
                    </div>
                </div>
            </div>
        </section>
        @endguest

        <!-- Footer -->
        <footer class="bg-light text-dark py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-birthday-cake text-warning fs-2 me-3"></i>
                            <h5 class="mb-0 fw-bold">{{ config('app.name', 'Bakery') }}</h5>
                        </div>
                        <p class="text-muted">
                            Bringing you the finest baked goods with love and tradition since day one.
                        </p>
                    </div>
                    
                    <div class="col-lg-2">
                        <h6 class="fw-bold mb-3">Quick Links</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('shop.index') }}" class="text-muted text-decoration-none">Shop</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">About Us</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2">
                        <h6 class="fw-bold mb-3">Services</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Delivery</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Pickup</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Catering</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4">
                        <h6 class="fw-bold mb-3">Connect With Us</h6>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-muted fs-4">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="text-muted fs-4">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-muted fs-4">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                <div class="text-center">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Bakery') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
