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

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <style>
            :root {
                --primary-color: #8B4513;
                --primary-dark: #654321;
                --secondary-color: #FFD700;
                --accent-color: #FF6B35;
            }
            
            body {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                min-height: 100vh;
                position: relative;
                overflow-x: hidden;
            }
            
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                opacity: 0.3;
                z-index: 1;
            }
            
            .auth-container {
                position: relative;
                z-index: 2;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 2rem 1rem;
            }
            
            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 1.5rem;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
                padding: 3rem 2rem;
                width: 100%;
                max-width: 450px;
                position: relative;
                overflow: hidden;
            }
            
            .auth-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
            }
            
            .logo-section {
                text-align: center;
                margin-bottom: 2rem;
            }
            
            .logo-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 2.5rem;
                color: white;
                box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
            }
            
            .form-control, .form-select {
                border: 2px solid #e9ecef;
                border-radius: 0.75rem;
                padding: 0.75rem 1rem;
                font-weight: 500;
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.9);
            }
            
            .form-control:focus, .form-select:focus {
                border-color: var(--secondary-color);
                box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
                background: white;
            }
            
            .form-label {
                font-weight: 600;
                color: var(--primary-color);
                margin-bottom: 0.5rem;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
                border: none;
                border-radius: 0.75rem;
                padding: 0.75rem 2rem;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(255, 215, 0, 0.4);
            }
            
            .btn-outline-primary {
                border: 2px solid var(--secondary-color);
                color: var(--secondary-color);
                background: transparent;
                border-radius: 0.75rem;
                padding: 0.75rem 2rem;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            
            .btn-outline-primary:hover {
                background: var(--secondary-color);
                color: var(--primary-color);
                transform: translateY(-2px);
            }
            
            .floating-shapes {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 1;
                pointer-events: none;
            }
            
            .shape {
                position: absolute;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                animation: float 8s ease-in-out infinite;
            }
            
            .shape:nth-child(1) {
                width: 100px;
                height: 100px;
                top: 10%;
                left: 10%;
                animation-delay: 0s;
            }
            
            .shape:nth-child(2) {
                width: 150px;
                height: 150px;
                top: 60%;
                right: 10%;
                animation-delay: 2s;
            }
            
            .shape:nth-child(3) {
                width: 80px;
                height: 80px;
                bottom: 20%;
                left: 20%;
                animation-delay: 4s;
            }
            
            .shape:nth-child(4) {
                width: 120px;
                height: 120px;
                top: 30%;
                right: 30%;
                animation-delay: 6s;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-30px) rotate(180deg); }
            }
            
            .back-link {
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                margin-top: 2rem;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .back-link:hover {
                color: white;
                transform: translateX(-5px);
            }
            
            .alert {
                border: none;
                border-radius: 0.75rem;
                font-weight: 500;
            }
            
            .alert-danger {
                background: linear-gradient(135deg, #f8d7da, #f5c6cb);
                color: #721c24;
            }
            
            .alert-success {
                background: linear-gradient(135deg, #d4edda, #c3e6cb);
                color: #155724;
            }
        </style>
    </head>
    <body>
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <div class="auth-container">
            <!-- Logo Section -->
            <div class="logo-section">
                <a href="/" class="text-decoration-none">
                    <div class="logo-icon">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <h1 class="text-white fw-bold mb-1">{{ config('app.name', 'Bakery') }}</h1>
                    <p class="text-white-50 mb-0">Fresh Baked Goodness</p>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="auth-card">
                {{ $slot }}
            </div>

            <!-- Back to Home Link -->
            <a href="/" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Home
            </a>
        </div>
    </body>
</html>
