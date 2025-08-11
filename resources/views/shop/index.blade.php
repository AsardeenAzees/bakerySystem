@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold text-primary">🍰 Our Bakery</h1>
            <p class="lead text-muted">Discover our delicious selection of fresh baked goods</p>
        </div>
        <div class="col-md-6">
            <!-- Search Form -->
            <form action="{{ route('shop.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control" placeholder="Search for cakes, breads...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Filters and Sort -->
    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Category Filter -->
            <div class="btn-group" role="group">
                <a href="{{ route('shop.index') }}" 
                   class="btn btn-outline-primary {{ !request('category') || request('category') === 'all' ? 'active' : '' }}">
                    All Categories
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" 
                       class="btn btn-outline-primary {{ request('category') === $category->slug ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="col-md-4">
            <!-- Sort Options -->
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort by Name</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="card-img-top" alt="{{ $product->name }}" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-birthday-cake fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 mb-0 text-primary">
                                        Rs. {{ number_format($product->effective_price, 2) }}
                                    </span>
                                    @if($product->discount_price)
                                        <small class="text-muted text-decoration-line-through">
                                            Rs. {{ number_format($product->price, 2) }}
                                        </small>
                                    @endif
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        @if($product->inventory && $product->inventory->quantity > 0)
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> In Stock
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                <i class="fas fa-times-circle"></i> Out of Stock
                                            </span>
                                        @endif
                                    </small>
                                    
                                    @if($product->inventory && $product->inventory->quantity > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cart-plus"></i> Add to Cart
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('shop.show', $product) }}" class="btn btn-outline-secondary btn-sm w-100">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h3>No products found</h3>
            <p class="text-muted">Try adjusting your search or filter criteria.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">View All Products</a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit sort form
    document.querySelector('select[name="sort"]').addEventListener('change', function() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = window.location.pathname;
        
        // Add current search and category params
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'search';
            input.value = urlParams.get('search');
            form.appendChild(input);
        }
        if (urlParams.has('category')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'category';
            input.value = urlParams.get('category');
            form.appendChild(input);
        }
        
        // Add sort param
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sort';
        input.value = this.value;
        form.appendChild(input);
        
        document.body.appendChild(form);
        form.submit();
    });
});
</script>
@endsection
