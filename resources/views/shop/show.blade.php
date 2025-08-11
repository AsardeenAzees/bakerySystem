@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="img-fluid rounded shadow" alt="{{ $product->name }}">
            @else
                <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" 
                     style="height: 400px;">
                    <i class="fas fa-birthday-cake fa-5x text-muted"></i>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="display-6 fw-bold text-primary mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                @if($product->discount_price)
                    <span class="h3 text-primary me-2">Rs. {{ number_format($product->discount_price, 2) }}</span>
                    <span class="h5 text-muted text-decoration-line-through">Rs. {{ number_format($product->price, 2) }}</span>
                @else
                    <span class="h3 text-primary">Rs. {{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <p class="lead mb-4">{{ $product->description }}</p>

            <!-- Stock Status -->
            <div class="mb-4">
                @if($product->inventory && $product->inventory->quantity > 0)
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-check-circle"></i> In Stock ({{ $product->inventory->quantity }} available)
                    </span>
                @else
                    <span class="badge bg-danger fs-6">
                        <i class="fas fa-times-circle"></i> Out of Stock
                    </span>
                @endif
            </div>

            <!-- Add to Cart Form -->
            @if($product->inventory && $product->inventory->quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <select name="quantity" id="quantity" class="form-select">
                                @for($i = 1; $i <= min(10, $product->inventory->quantity); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            <!-- Product Meta -->
            <div class="border-top pt-4">
                <div class="row text-muted">
                    <div class="col-6">
                        <strong>Category:</strong><br>
                        {{ $product->category->name }}
                    </div>
                    <div class="col-6">
                        <strong>SKU:</strong><br>
                        {{ $product->id }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Customer Reviews</h3>
            
            @if($product->reviews->count() > 0)
                <!-- Review Stats -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-primary mb-1">{{ number_format($product->reviews->avg('rating'), 1) }}</h4>
                            <div class="text-warning mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $product->reviews->avg('rating'))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $product->reviews->avg('rating'))
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">{{ $product->reviews->count() }} reviews</small>
                        </div>
                    </div>
                    
                    <!-- Rating Distribution -->
                    <div class="col-md-8">
                        @for($rating = 5; $rating >= 1; $rating--)
                            @php
                                $count = $product->reviews->where('rating', $rating)->count();
                                $percentage = $product->reviews->count() > 0 ? ($count / $product->reviews->count()) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">{{ $rating }} <i class="fas fa-star text-warning"></i></span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-muted">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Individual Reviews -->
                <div class="row">
                    @foreach($product->reviews as $review)
                        <div class="col-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $review->user->name }}</h6>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($review->comment)
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <h5>No reviews yet</h5>
                    <p class="text-muted">Be the first to review this product!</p>
                </div>
            @endif

            <!-- Add Review Form -->
            @if(auth()->check())
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Write a Review</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                        <label for="star{{ $i }}" class="star-label">
                                            <i class="far fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment (Optional)</label>
                                <textarea name="comment" id="comment" rows="3" class="form-control" 
                                          placeholder="Share your experience with this product..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">You might also like</h3>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col">
                            <div class="card h-100">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                                         class="card-img-top" alt="{{ $relatedProduct->name }}" 
                                         style="height: 150px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 150px;">
                                        <i class="fas fa-birthday-cake fa-2x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                                    <p class="text-primary fw-bold">Rs. {{ number_format($relatedProduct->effective_price, 2) }}</p>
                                    <a href="{{ route('shop.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 5px;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    cursor: pointer;
    font-size: 24px;
    color: #ddd;
    transition: color 0.2s;
}

.star-label:hover,
.star-label:hover ~ .star-label,
.rating-input input[type="radio"]:checked ~ .star-label {
    color: #ffc107;
}

.star-label i {
    transition: transform 0.2s;
}

.star-label:hover i,
.rating-input input[type="radio"]:checked ~ .star-label i {
    transform: scale(1.1);
}
</style>
@endsection
