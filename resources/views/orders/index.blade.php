@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="display-5 fw-bold text-primary mb-4">📦 My Orders</h1>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Order #{{ $order->id }}</h5>
                                <small class="text-muted">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge fs-6 
                                    @switch($order->status)
                                        @case('pending') bg-warning
                                        @case('processing') bg-info
                                        @case('ready') bg-primary
                                        @case('out_for_delivery') bg-info
                                        @case('delivered') bg-success
                                        @case('cancelled') bg-danger
                                        @default bg-secondary
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                                <div class="mt-1">
                                    <strong class="text-primary">Rs. {{ number_format($order->total, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Order Items -->
                            <div class="row mb-3">
                                @foreach($order->items as $item)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     class="rounded me-3" alt="{{ $item->product->name }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-birthday-cake text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                <small class="text-muted">
                                                    Qty: {{ $item->qty }} × Rs. {{ number_format($item->unit_price, 2) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Order Details -->
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="text-muted">Delivery Address</h6>
                                    <p class="mb-1">{{ $order->address->line1 }}</p>
                                    @if($order->address->line2)
                                        <p class="mb-1">{{ $order->address->line2 }}</p>
                                    @endif
                                    <p class="mb-1">{{ $order->address->city }}, {{ $order->address->state }}</p>
                                    <p class="mb-0">{{ $order->address->postal_code }}, {{ $order->address->country }}</p>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6 class="text-muted">Payment</h6>
                                    <p class="mb-1">
                                        <strong>Method:</strong> 
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment->provider) }}</span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Status:</strong> 
                                        <span class="badge 
                                            @if($order->payment->status === 'succeeded') bg-success
                                            @elseif($order->payment->status === 'pending') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Amount:</strong> Rs. {{ number_format($order->payment->amount, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6 class="text-muted">Order Summary</h6>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Subtotal:</span>
                                        <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Delivery:</span>
                                        <span>Rs. {{ number_format($order->delivery_fee, 2) }}</span>
                                    </div>
                                    @if($order->discount > 0)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Discount:</span>
                                            <span class="text-success">-Rs. {{ number_format($order->discount, 2) }}</span>
                                        </div>
                                    @endif
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <strong>Rs. {{ number_format($order->total, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Actions -->
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <div>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </div>
                                
                                <div>
                                    @if(in_array($order->status, ['pending', 'processing']))
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                                <i class="fas fa-times"></i> Cancel Order
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($order->status === 'delivered')
                                        <a href="{{ route('shop.show', $order->items->first()->product) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-star"></i> Write Review
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <!-- No Orders -->
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-5x text-muted mb-4"></i>
            <h3>No orders yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your order history here.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-utensils"></i> Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
