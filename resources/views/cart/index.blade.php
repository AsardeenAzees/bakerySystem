@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="display-5 fw-bold text-primary mb-4">🛒 Shopping Cart</h1>

    @if(count($items) > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart"></i> Cart Items ({{ count($items) }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($items as $item)
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-md-2">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                             class="img-fluid rounded" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="height: 80px; width: 80px;">
                                            <i class="fas fa-birthday-cake fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <p class="text-muted mb-0">Unit Price: Rs. {{ number_format($item['price'], 2) }}</p>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" 
                                                onclick="updateQuantity({{ $item['id'] }}, {{ $item['qty'] - 1 }})">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="form-control text-center" 
                                               value="{{ $item['qty'] }}" min="1" max="10" readonly>
                                        <button type="button" class="btn btn-outline-secondary" 
                                                onclick="updateQuantity({{ $item['id'] }}, {{ $item['qty'] + 1 }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 text-center">
                                    <h6 class="text-primary mb-0">Rs. {{ number_format($item['price'] * $item['qty'], 2) }}</h6>
                                </div>
                                
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="removeItem({{ $item['id'] }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Cart Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3">
                            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                            
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to clear your cart?')">
                                    <i class="fas fa-trash"></i> Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt"></i> Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rs. {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <span>Rs. 250.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span>Rs. 0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-success fs-5">Rs. {{ number_format($subtotal + 250.00, 2) }}</strong>
                        </div>
                        
                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-credit-card"></i> Proceed to Checkout
                        </a>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-lock"></i> Secure checkout with Stripe
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any delicious treats to your cart yet.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-utensils"></i> Start Shopping
            </a>
        </div>
    @endif
</div>

<script>
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1 || newQuantity > 10) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("cart.update") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    form.appendChild(methodField);
    
    const productIdField = document.createElement('input');
    productIdField.type = 'hidden';
    productIdField.name = 'product_id';
    productIdField.value = productId;
    form.appendChild(productIdField);
    
    const quantityField = document.createElement('input');
    quantityField.type = 'hidden';
    quantityField.name = 'quantity';
    quantityField.value = newQuantity;
    form.appendChild(quantityField);
    
    document.body.appendChild(form);
    form.submit();
}

function removeItem(productId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("cart.remove") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        const productIdField = document.createElement('input');
        productIdField.type = 'hidden';
        productIdField.name = 'product_id';
        productIdField.value = productId;
        form.appendChild(productIdField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
