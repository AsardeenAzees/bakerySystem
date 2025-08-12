@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-4">Admin Dashboard</h1>

  <div class="row g-3">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="h6 text-muted">Orders Today</div>
          <div class="h3">{{ $kpis['orders_today'] }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="h6 text-muted">Revenue Today</div>
          <div class="h3">Rs {{ number_format($kpis['revenue_today'],2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="h6 text-muted">Pending/Processing</div>
          <div class="h3">{{ $kpis['pending_orders'] }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="h6 text-muted">Low Stock Items</div>
          <div class="h3">{{ $kpis['low_stock'] }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="row g-3 mb-4 mt-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Quick Actions</h5>
        </div>
        <div class="card-body">
          <div class="row g-2">
            <div class="col-md-3">
              <a href="{{ route('admin.orders.index') }}" class="btn btn-primary w-100">
                <i class="fas fa-shopping-cart"></i> Manage Orders
              </a>
            </div>
            <div class="col-md-3">
              <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-box"></i> Manage Products
              </a>
            </div>
            <div class="col-md-3">
              <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-tags"></i> Manage Categories
              </a>
            </div>
            <div class="col-md-3">
              <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-chart-bar"></i> View Reports
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">Last 7 Days Sales</div>
    <div class="card-body">
      <canvas id="sales7"></canvas>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($sales->pluck('date'));
const data = @json($sales->pluck('amount'));

  new Chart(document.getElementById('sales7'), {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Revenue (Rs)',
        data,
        borderColor: '#6f42c1',
        backgroundColor: 'rgba(111, 66, 193, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Daily Revenue Trend'
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rs ' + value.toLocaleString();
            }
          }
        }
      }
    }
  });
</script>
@endpush
@endsection