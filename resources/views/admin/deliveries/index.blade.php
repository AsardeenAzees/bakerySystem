@extends('layouts.app')
@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Deliveries</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Orders</a>
  </div>

  @include('partials.flash')

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fas fa-truck"></i> All Deliveries</h5>
      <div class="d-flex align-items-center">
        <small class="text-muted me-2" id="lastUpdated">Last updated: {{ now()->diffForHumans() }}</small>
        <button id="refreshBtn" class="btn btn-sm btn-outline-primary">Refresh</button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="deliveriesTable">
          <thead class="table-light">
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Address</th>
              <th>Assigned To</th>
              <th>Payment</th>
              <th>Status</th>
              <th>Updated</th>
            </tr>
          </thead>
          <tbody>
            @foreach($deliveries as $delivery)
            <tr>
              <td>
                <a href="{{ route('admin.orders.show', $delivery->order) }}">#{{ $delivery->order->id }}</a>
              </td>
              <td>
                <strong>{{ $delivery->order->user->name }}</strong><br>
                <small class="text-muted">{{ $delivery->order->user->email }}</small>
              </td>
              <td>
                {{ $delivery->order->address->line1 }}
                @if($delivery->order->address->line2)<br>{{ $delivery->order->address->line2 }}@endif
                <br>{{ $delivery->order->address->city }}@if($delivery->order->address->state), {{ $delivery->order->address->state }}@endif
              </td>
              <td>
                {{ $delivery->assignedUser?->name ?? 'Unassigned' }}
              </td>
              <td>
                @if($delivery->order->payment)
                  <span class="badge bg-secondary">{{ strtoupper($delivery->order->payment->provider) }}</span>
                  <small class="text-muted d-block">{{ ucfirst($delivery->order->payment->status) }}</small>
                @else
                  <span class="badge bg-warning">Pending</span>
                @endif
              </td>
              <td>
                @php($st = $delivery->status)
                <span class="badge @switch($st) @case('pending') bg-warning text-dark @break @case('assigned') bg-info @break @case('in_transit') bg-primary @break @case('delivered') bg-success @break @default bg-secondary @endswitch">
                  {{ $st === 'in_transit' ? 'Out for Delivery' : ucfirst(str_replace('_',' ',$st)) }}
                </span>
              </td>
              <td>
                <small class="text-muted">{{ $delivery->updated_at->diffForHumans() }}</small>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!--Pagination-->
      <div class="d-flex justify-content-center mt-5">
          {{ $deliveries->links('pagination::bootstrap-5') }}
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
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const endpoint = "{{ route('admin.deliveries.json') }}";
    const refreshBtn = document.getElementById('refreshBtn');
    const tableBody = document.querySelector('#deliveriesTable tbody');

    function render(deliveries){
      tableBody.innerHTML = deliveries.map(d => {
        const address = d.order.address;
        const payment = d.order.payment;
        const user    = d.order.user;
        
        // Get proper status badge class
        let statusBadgeClass = 'badge bg-secondary';
        let statusText = d.status || '';
        
        switch(statusText) {
          case 'pending':
            statusBadgeClass = 'badge bg-warning text-dark';
            break;
          case 'assigned':
            statusBadgeClass = 'badge bg-info';
            break;
          case 'in_transit':
            statusBadgeClass = 'badge bg-primary';
            statusText = 'Out for Delivery';
            break;
          case 'delivered':
            statusBadgeClass = 'badge bg-success';
            break;
          default:
            statusText = statusText.replaceAll('_', ' ');
        }
        
        // Format payment status
        let paymentStatus = '';
        if (payment) {
          paymentStatus = `<span class="badge bg-secondary">${(payment.provider || '').toUpperCase()}</span><small class="text-muted d-block">${(payment.status || '').charAt(0).toUpperCase() + (payment.status || '').slice(1)}</small>`;
        } else {
          paymentStatus = '<span class="badge bg-warning">Pending</span>';
        }
        
        return `
          <tr>
            <td><a href="/admin/orders/${d.order.id}">#${d.order.id}</a></td>
            <td><strong>${user.name || ''}</strong><br><small class="text-muted">${user.email || ''}</small></td>
            <td>${address.line1 || ''}${address.line2 ? '<br>'+address.line2 : ''}<br>${address.city || ''}${address.state ? ', '+address.state : ''}</td>
            <td>${d.assigned_user ? d.assigned_user.name : 'Unassigned'}</td>
            <td>${paymentStatus}</td>
            <td><span class="${statusBadgeClass}">${statusText}</span></td>
            <td><small class="text-muted">${d.updated_at ? new Date(d.updated_at).toLocaleString() : 'Unknown'}</small></td>
          </tr>`
      }).join('');
    }

    async function fetchData(){
      try{
        // Show loading state
        if (refreshBtn) {
          refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
          refreshBtn.disabled = true;
        }
        
        const res = await fetch(endpoint, {headers: {'Accept':'application/json'}});
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }
        const data = await res.json();
        
        // Validate data structure before rendering
        if (Array.isArray(data) && data.length > 0 && data[0].order) {
          render(data);
          
          // Update last updated timestamp
          const lastUpdated = document.getElementById('lastUpdated');
          if (lastUpdated) {
            lastUpdated.textContent = `Last updated: ${new Date().toLocaleTimeString()}`;
          }
        } else {
          console.warn('Invalid data structure received:', data);
        }
      }catch(e){ 
        console.error('Error fetching delivery data:', e);
        // Don't break the UI on error, just log it
      } finally {
        // Reset button state
        if (refreshBtn) {
          refreshBtn.innerHTML = 'Refresh';
          refreshBtn.disabled = false;
        }
      }
    }

    refreshBtn?.addEventListener('click', fetchData);
    
    // Start auto-refresh after a delay to avoid immediate refresh on page load
    setTimeout(() => {
      setInterval(fetchData, 8000);
    }, 5000);
    
    // Add error handling for initial page load
    window.addEventListener('error', function(e) {
      console.error('Page error:', e);
    });
  })();
</script>
@endpush
@endsection


