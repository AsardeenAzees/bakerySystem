<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            {{ config('app.name', 'Bakery') }}
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Side -->
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->hasAnyRole(['customer','admin']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.index') }}">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                Cart
                                @php
                                    $cartCount = collect(session('cart', []))->sum('qty');
                                @endphp
                                @if($cartCount > 0)
                                    <span class="badge text-bg-primary">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('addresses.index') }}">Addresses</a>
                        </li>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.orders.index') }}">Orders</a>
                        </li>
                    @endif

                    @if(auth()->user()->isChef() || auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('chef.tasks') }}">Chef</a>
                        </li>
                    @endif

                    @if(auth()->user()->isDelivery() || auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('delivery.list') }}">Delivery</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- Right Side -->
            <ul class="navbar-nav ms-auto">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                            <span class="badge rounded-pill text-bg-secondary ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->hasAnyRole(['customer','admin']))
                                <li><a class="dropdown-item" href="{{ route('shop.index') }}">Shop</a></li>
                                <li><a class="dropdown-item" href="{{ route('cart.index') }}">Cart</a></li>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Orders</a></li>
                            @endif

                            @if(auth()->user()->isChef() || auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('chef.tasks') }}">Chef</a></li>
                            @endif

                            @if(auth()->user()->isDelivery() || auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('delivery.list') }}">Delivery</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
