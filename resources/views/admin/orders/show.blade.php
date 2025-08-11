@extends('layouts.app')
@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Order #{{ $order->id }} Details</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
  </div>

  @include('partials.flash')

  <div class="row">
    <!-- Order Information -->
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Order Information</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Order ID:</strong> #{{ $order->id }}</p>
              <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y g:i A') }}</p>
              <p><strong>Status:</strong> 
                <span class="badge {{ $order->status_badge }}">{{ $order->status_display }}</span>
              </p>
            </div>
            <div class="col-md-6">
              <p><strong>Subtotal:</strong> Rs {{ number_format($order->subtotal, 2) }}</p>
              <p><strong>Delivery Fee:</strong> Rs {{ number_format($order->delivery_fee, 2) }}</p>
              <p><strong>Discount:</strong> Rs {{ number_format($order->discount, 2) }}</p>
              <p><strong>Total:</strong> <span class="h5 text-primary">Rs {{ number_format($order->total, 2) }}</span></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Customer Information -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Customer Information</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Name:</strong> {{ $order->user->name }}</p>
              <p><strong>Email:</strong> {{ $order->user->email }}</p>
              <p><strong>Phone:</strong> {{ $order->user->phone ?? 'Not provided' }}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Delivery Address:</strong></p>
              <div class="border rounded p-2 bg-light">
                {{ $order->address->line1 }}<br>
                @if($order->address->line2){{ $order->address->line2 }}<br>@endif
                {{ $order->address->city }}
                @if($order->address->state), {{ $order->address->state }}@endif
                @if($order->address->postal_code)<br>{{ $order->address->postal_code }}@endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Order Items</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $item)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      @if($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                      @endif
                      <div>
                        <strong>{{ $item->product->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $item->product->category->name }}</small>
                      </div>
                    </div>
                  </td>
                  <td>Rs {{ number_format($item->price, 2) }}</td>
                  <td>{{ $item->qty }}</td>
                  <td>Rs {{ number_format($item->price * $item->qty, 2) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Delivery & Payment Information -->
    <div class="col-md-4">
      <!-- Payment Information -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Payment Information</h5>
        </div>
        <div class="card-body">
          @if($order->payment)
            <p><strong>Provider:</strong> {{ strtoupper($order->payment->provider) }}</p>
            <p><strong>Status:</strong> 
              <span class="badge bg-{{ $order->payment->status === 'completed' ? 'success' : 'warning' }}">
                {{ ucfirst($order->payment->status) }}
              </span>
            </p>
            <p><strong>Transaction ID:</strong> {{ $order->payment->transaction_id ?? 'N/A' }}</p>
            <p><strong>Amount:</strong> Rs {{ number_format($order->payment->amount, 2) }}</p>
          @else
            <p class="text-muted">No payment information available</p>
          @endif
        </div>
      </div>

      <!-- Delivery Information -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Delivery Information</h5>
        </div>
        <div class="card-body">
          @if($order->delivery)
            <p><strong>Status:</strong> 
              <span class="badge {{ $order->delivery->status_badge }}">
                {{ ucfirst(str_replace('_', ' ', $order->delivery->status)) }}
              </span>
            </p>
            @if($order->delivery->assignedUser)
              <p><strong>Assigned To:</strong> {{ $order->delivery->assignedUser->name }}</p>
            @endif
            @if($order->delivery->delivered_at)
              <p><strong>Delivered At:</strong> {{ $order->delivery->delivered_at->format('M d, Y g:i A') }}</p>
            @endif
            @if($order->delivery->notes)
              <p><strong>Notes:</strong> {{ $order->delivery->notes }}</p>
            @endif
          @else
            <p class="text-muted">No delivery information available</p>
          @endif
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Quick Actions</h5>
        </div>
        <div class="card-body">
          <!-- Proceed to Delivery Button -->
          @if($order->canProceedToDelivery())
            <form method="post" action="{{ route('admin.orders.proceedDelivery', $order) }}" class="mb-3">
              @csrf
              <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-truck"></i> Proceed to Delivery
              </button>
            </form>
          @endif

          <!-- Assign Delivery Staff -->
          <form method="post" action="{{ route('admin.orders.assignDelivery', $order) }}" class="mb-3">
            @csrf
            <label class="form-label">Assign Delivery Staff:</label>
            <select name="assigned_to" class="form-select mb-2">
              <option value="">Select delivery staff</option>
              @foreach(\App\Models\User::where('role', 'delivery')->orderBy('name')->get() as $user)
                <option value="{{ $user->id }}" @selected($order->delivery?->assigned_to == $user->id)>
                  {{ $user->name }}
                </option>
              @endforeach
            </select>
            <button type="submit" class="btn btn-outline-primary w-100">Assign</button>
          </form>

          <!-- Update Status -->
          <form method="post" action="{{ route('admin.orders.status', $order) }}" class="mb-3">
            @csrf @method('PATCH')
            <label class="form-label">Update Status:</label>
            <select name="status" class="form-select mb-2">
              @foreach(['pending','processing','ready','awaiting_delivery_pickup','out_for_delivery','delivered','cancelled'] as $status)
                <option value="{{ $status }}" @selected($order->status == $status)>
                  {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
              @endforeach
            </select>
            <button type="submit" class="btn btn-outline-secondary w-100">Update Status</button>
          </form>

          <!-- Refund Button -->
          @if(in_array($order->status, ['delivered', 'processing', 'out_for_delivery']))
            <form method="post" action="{{ route('admin.orders.refund', $order) }}">
              @csrf
              <button type="submit" class="btn btn-outline-danger w-100" 
                      onclick="return confirm('Are you sure you want to refund this order? This will restore inventory.')">
                <i class="fas fa-undo"></i> Refund Order
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
