@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">My Deliveries</h1>
  @include('partials.flash')

  @if($deliveries->count() > 0)
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
          <i class="fas fa-truck"></i> 
          {{ $deliveries->count() }} delivery assignment(s)
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Delivery Address</th>
                 <th>Status</th>
                 <th>Payment</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($deliveries as $delivery)
              <tr>
                <td>
                  <strong>#{{ $delivery->order->id }}</strong>
                  <br>
                  <small class="text-muted">{{ $delivery->order->created_at->format('M d, Y') }}</small>
                </td>
                <td>
                  <strong>{{ $delivery->order->user->name }}</strong>
                  <br>
                  <small class="text-muted">{{ $delivery->order->user->phone ?? 'No phone' }}</small>
                </td>
                <td>
                  <div class="order-items">
                    @foreach($delivery->order->items->take(2) as $item)
                      <div>{{ $item->product->name }} x{{ $item->qty }}</div>
                    @endforeach
                    @if($delivery->order->items->count() > 2)
                      <small class="text-muted">+{{ $delivery->order->items->count() - 2 }} more items</small>
                    @endif
                  </div>
                </td>
                <td>
                  <div class="delivery-address">
                    <strong>{{ $delivery->order->address->line1 }}</strong>
                    @if($delivery->order->address->line2)
                      <br>{{ $delivery->order->address->line2 }}
                    @endif
                    <br>{{ $delivery->order->address->city }}
                    @if($delivery->order->address->state)
                      , {{ $delivery->order->address->state }}
                    @endif
                    @if($delivery->order->address->postal_code)
                      <br>{{ $delivery->order->address->postal_code }}
                    @endif
                  </div>
                </td>
                <td>
                  @switch($delivery->status)
                    @case('pending')
                      <span class="badge bg-warning text-dark">Pending</span>
                      @break
                    @case('assigned')
                      <span class="badge bg-info">Assigned</span>
                      @break
                    @case('in_transit')
                      <span class="badge bg-primary">In Transit</span>
                      @break
                    @case('delivered')
                      <span class="badge bg-success">Delivered</span>
                      @break
                    @default
                      <span class="badge bg-secondary">{{ ucfirst($delivery->status) }}</span>
                  @endswitch
                  <br>
                  <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $delivery->order->status)) }}</small>
                </td>
                <td>
                  @if($delivery->order->payment)
                    <span class="badge bg-secondary">{{ strtoupper($delivery->order->payment->provider) }}</span>
                    <br>
                    <small class="text-muted">{{ ucfirst($delivery->order->payment->status) }}</small>
                  @else
                    <span class="badge bg-warning">Pending</span>
                  @endif
                </td>
                <td class="text-end">
                  @if($delivery->order->status === 'awaiting_delivery_pickup')
                    <form method="post" action="{{ route('delivery.pickup', $delivery->order) }}" class="d-inline mb-2">
                      @csrf
                      <button class="btn btn-sm btn-primary" onclick="return confirm('Pick up this order and start delivery?')">
                        <i class="fas fa-truck"></i> Pickup & Out for Delivery
                      </button>
                    </form>
                  @elseif($delivery->order->status === 'out_for_delivery')
                    <form method="post" action="{{ route('delivery.delivered', $delivery->order) }}" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-success" onclick="return confirm('Mark this order as delivered?')">
                        <i class="fas fa-check"></i> Delivered Successfully
                      </button>
                    </form>
                    @if($delivery->order->payment && $delivery->order->payment->provider === 'cod')
                      <button class="btn btn-sm btn-outline-secondary ms-1" disabled title="Confirm after delivery">Payment Received</button>
                    @endif
                  @elseif($delivery->order->status === 'delivered')
                    <span class="badge bg-success fs-6">
                      <i class="fas fa-check-circle"></i> Delivered
                    </span>
                    @if($delivery->delivered_at)
                      <br>
                      <small class="text-muted">{{ $delivery->delivered_at->format('M d, Y g:i A') }}</small>
                    @endif
                    @if($delivery->order->payment && $delivery->order->payment->provider === 'cod' && $delivery->order->payment->status !== 'succeeded')
                      <form method="post" action="{{ route('delivery.paymentReceived', $delivery->order) }}" class="d-inline mt-2">
                        @csrf
                        <button class="btn btn-sm btn-primary" onclick="return confirm('Confirm COD payment received?')">
                          <i class="fas fa-money-bill-wave"></i> Payment Received
                        </button>
                      </form>
                    @endif
                  @else
                    <span class="text-muted">No action available</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @else
    <div class="text-center py-5">
      <i class="fas fa-truck fa-5x text-muted mb-3"></i>
      <h3>No assigned deliveries</h3>
      <p class="text-muted">You don't have any delivery assignments at the moment.</p>
    </div>
  @endif
</div>

<style>
.delivery-address {
  font-size: 0.9rem;
  line-height: 1.4;
}
.order-items {
  font-size: 0.9rem;
  line-height: 1.3;
}
</style>
@endsection
