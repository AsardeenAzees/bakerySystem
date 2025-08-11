@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Order #{{ $order->id }}</h4>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Date:</strong><br>
                            {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Last Updated:</strong><br>
                            {{ $order->updated_at->format('M d, Y \a\t g:i A') }}
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h5 class="mb-3">Order Items</h5>
                    @foreach($order->items as $item)
                        <div class="row align-items-center py-3 border-bottom">
                            <div class="col-md-2">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         class="img-fluid rounded" alt="{{ $item->product->name }}">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 60px; width: 60px;">
                                        <i class="fas fa-birthday-cake text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <small class="text-muted">{{ $item->product->category->name }}</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-secondary">{{ $item->qty }}</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <strong>Rs. {{ number_format($item->line_total, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach

                    <!-- Order Summary -->
                    <div class="row mt-4">
                        <div class="col-md-6 offset-md-6">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-3">Order Summary</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Delivery Fee:</span>
                                    <span>Rs. {{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                                @if($order->discount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount:</span>
                                        <span class="text-success">-Rs. {{ number_format($order->discount, 2) }}</span>
                                    </div>
                                @endif
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong class="text-primary fs-5">Rs. {{ number_format($order->total, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-truck"></i> Delivery Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Delivery Address</h6>
                            <p class="mb-1">{{ $order->address->line1 }}</p>
                            @if($order->address->line2)
                                <p class="mb-1">{{ $order->address->line2 }}</p>
                            @endif
                            <p class="mb-1">{{ $order->address->city }}, {{ $order->address->state }}</p>
                            <p class="mb-0">{{ $order->address->postal_code }}, {{ $order->address->country }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Delivery Status</h6>
                            <p class="mb-1">
                                <strong>Status:</strong> 
                                <span class="badge 
                                    @switch($order->delivery->status)
                                        @case('pending') bg-warning
                                        @case('assigned') bg-info
                                        @case('out_for_delivery') bg-primary
                                        @case('delivered') bg-success
                                        @default bg-secondary
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $order->delivery->status)) }}
                                </span>
                            </p>
                            @if($order->delivery->assigned_to)
                                <p class="mb-1">
                                    <strong>Assigned To:</strong> {{ $order->delivery->assigned_to }}
                                </p>
                            @endif
                            @if($order->delivery->delivered_at)
                                <p class="mb-0">
                                    <strong>Delivered At:</strong> {{ $order->delivery->delivered_at->format('M d, Y \a\t g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Method</h6>
                            <p class="mb-1">
                                <strong>Provider:</strong> {{ ucfirst($order->payment->provider) }}
                            </p>
                            <p class="mb-1">
                                <strong>Status:</strong> 
                                <span class="badge 
                                    @if($order->payment->status === 'completed') bg-success
                                    @elseif($order->payment->status === 'pending') bg-warning
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Details</h6>
                            <p class="mb-1">
                                <strong>Amount:</strong> Rs. {{ number_format($order->payment->amount, 2) }}
                            </p>
                            @if($order->payment->transaction_id)
                                <p class="mb-1">
                                    <strong>Transaction ID:</strong> {{ $order->payment->transaction_id }}
                                </p>
                            @endif
                            @if($order->payment->paid_at)
                                <p class="mb-0">
                                    <strong>Paid At:</strong> {{ $order->payment->paid_at->format('M d, Y \a\t g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Actions & Timeline -->
        <div class="col-lg-4">
            <!-- Order Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Order Actions</h5>
                </div>
                <div class="card-body">
                    @if(in_array($order->status, ['pending', 'processing']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                <i class="fas fa-times"></i> Cancel Order
                            </button>
                        </form>
                    @endif
                    
                    @if($order->status === 'delivered')
                        <a href="{{ route('shop.show', $order->items->first()->product) }}" 
                           class="btn btn-outline-success w-100">
                            <i class="fas fa-star"></i> Write Review
                        </a>
                    @endif
                    
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Order Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Placed</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                        
                        @if($order->status !== 'pending')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Processing</h6>
                                    <small class="text-muted">Order confirmed and being prepared</small>
                                </div>
                            </div>
                        @endif
                        
                        @if(in_array($order->status, ['ready', 'out_for_delivery', 'delivered']))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Ready for Delivery</h6>
                                    <small class="text-muted">Your order is ready and being prepared for delivery</small>
                                </div>
                            </div>
                        @endif
                        
                        @if(in_array($order->status, ['out_for_delivery', 'delivered']))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Out for Delivery</h6>
                                    <small class="text-muted">Your order is on its way to you</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->status === 'delivered')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Delivered</h6>
                                    <small class="text-muted">Your order has been delivered successfully!</small>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->status === 'cancelled')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Cancelled</h6>
                                    <small class="text-muted">This order has been cancelled</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -18px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-content {
    margin-left: 10px;
}
</style>
@endsection
