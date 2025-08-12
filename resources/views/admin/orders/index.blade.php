@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">Orders Management</h1>
  @include('partials.flash')

  <form class="row g-2 mb-3" method="get">
    <div class="col-md-3">
      <select name="status" class="form-select" onchange="this.form.submit()">
        <option value="">All statuses</option>
        @foreach(['pending','processing','ready','awaiting_delivery_pickup','out_for_delivery','delivered','cancelled','refunded'] as $s)
        <option value="{{ $s }}" @selected($status==$s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
        @endforeach
      </select>
    </div>
  </form>





  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Items</th>
          <th>Total</th>
          <th>Status</th>
          <th>Payment</th>
          <th>Delivery</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <td>
            <strong>#{{ $order->id }}</strong>
            <br>
            <small class="text-muted">{{ $order->created_at->format('M d, Y g:i A') }}</small>
          </td>
          <td>
            <strong>{{ $order->user->name }}</strong>
            <br>
            <small class="text-muted">{{ $order->user->email }}</small>
          </td>
          <td>
            <div class="order-items">
              @foreach($order->items->take(2) as $item)
                <div>{{ $item->product->name }} x{{ $item->qty }}</div>
              @endforeach
              @if($order->items->count() > 2)
                <small class="text-muted">+{{ $order->items->count() - 2 }} more items</small>
              @endif
            </div>
          </td>
          <td>
            <strong>Rs {{ number_format($order->total,2) }}</strong>
            <br>
            <small class="text-muted">{{ $order->items->count() }} items</small>
          </td>
          <td>
            <span class="badge {{ $order->status_badge }}">{{ $order->status_display }}</span>
          </td>
          <td>
            @if($order->payment)
              <span class="badge bg-info">{{ strtoupper($order->payment->provider) }}</span>
              <br>
              <small class="text-muted">{{ ucfirst($order->payment->status) }}</small>
            @else
              <span class="badge bg-warning">Pending</span>
            @endif
          </td>
          <td>
            <form method="post" action="{{ route('admin.orders.assignDelivery',$order) }}" class="d-flex gap-2">
              @csrf
              <select name="assigned_to" class="form-select form-select-sm">
                <option value="">Unassigned</option>
                @foreach($deliveryUsers as $user)
                <option value="{{ $user->id }}" @selected($order->delivery?->assigned_to==$user->id)>{{ $user->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-sm btn-outline-primary">Assign</button>
            </form>
            @if($order->delivery?->assignedUser)
              <small class="text-muted">Assigned to: {{ $order->delivery->assignedUser->name }}</small>
            @endif
          </td>
          <td>
            <div class="dropdown d-inline">
              <button class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
              <div class="dropdown-menu dropdown-menu-end p-2">
                <!-- Proceed to Delivery Button -->
                @if($order->canProceedToDelivery())
                <form method="post" action="{{ route('admin.orders.proceedDelivery',$order) }}" class="mb-2">
                  @csrf
                  <button class="btn btn-sm btn-success w-100">
                    <i class="fas fa-truck"></i> Proceed to Delivery
                  </button>
                </form>
                @endif
                
                <!-- Status Update Form -->
                <form method="post" action="{{ route('admin.orders.status',$order) }}" class="mb-2">
                  @csrf @method('PATCH')
                  <select name="status" class="form-select form-select-sm mb-2">
                    @foreach(['pending','processing','ready','awaiting_delivery_pickup','out_for_delivery','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" @selected($order->status==$s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                  </select>
                  <button class="btn btn-sm btn-primary w-100">Update Status</button>
                </form>
                
                <!-- Refund Form -->
                @if(in_array($order->status, ['delivered', 'processing', 'out_for_delivery']))
                <form method="post" action="{{ route('admin.orders.refund',$order) }}" class="mt-2">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Confirm refund? This will restore inventory.')">Refund</button>
                </form>
                @endif
              </div>
            </div>
            
            <a class="btn btn-sm btn-outline-secondary ms-1" href="{{ route('admin.orders.show',$order) }}">View Details</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!--Pagination-->
  <div class="d-flex justify-content-center mt-5">
      {{ $orders->links('pagination::bootstrap-5') }}
  </div>

  <style>
      .pagination .page-item.active .page-link {
          background-color: #0d6efd;
          border-color: #0d6efd;
      }
      .pagination .page-link {
          color: #0d6efd;
      }
      .pagination .page-link:hover {
          background-color: #e9ecef;
      }
  </style>

</div>

<style>
.order-items {
  font-size: 0.9rem;
  line-height: 1.3;
}
</style>
@endsection