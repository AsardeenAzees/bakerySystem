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
      <button id="refreshBtn" class="btn btn-sm btn-outline-primary">Refresh</button>
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

      {{ $deliveries->links() }}
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
        const statusBadge = 'badge';
        return `
          <tr>
            <td><a href="/admin/orders/${d.order.id}">#${d.order.id}</a></td>
            <td><strong>${user.name}</strong><br><small class="text-muted">${user.email ?? ''}</small></td>
            <td>${address.line1}${address.line2 ? '<br>'+address.line2: ''}<br>${address.city}${address.state ? ', '+address.state : ''}</td>
            <td>${d.assigned_user ? d.assigned_user.name : (d.assigned_user_id ?? 'Unassigned')}</td>
            <td>${payment ? `<span class="badge bg-secondary">${payment.provider?.toUpperCase?.() || payment.provider}</span><small class="text-muted d-block">${(payment.status||'').charAt(0).toUpperCase()+ (payment.status||'').slice(1)}</small>` : '<span class="badge bg-warning">Pending</span>'}</td>
            <td><span class="badge">${(d.status||'').replaceAll('_',' ')}</span></td>
            <td><small class="text-muted">just now</small></td>
          </tr>`
      }).join('');
    }

    async function fetchData(){
      try{
        const res = await fetch(endpoint, {headers: {'Accept':'application/json'}});
        const data = await res.json();
        render(data);
      }catch(e){ console.error(e); }
    }

    refreshBtn?.addEventListener('click', fetchData);
    setInterval(fetchData, 8000);
  })();
</script>
@endpush
@endsection


