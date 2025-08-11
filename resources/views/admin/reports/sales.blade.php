@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">Sales Report</h1>
  <form class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="from" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($from)->toDateString() }}"></div>
    <div class="col-md-3"><input type="date" name="to" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($to)->toDateString() }}"></div>
    <div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div>
  </form>

  <div class="card mb-3"><div class="card-body">
    <canvas id="sales"></canvas>
  </div></div>

  <table class="table">
    <thead><tr><th>Date</th><th>Orders</th><th>Revenue (Rs)</th></tr></thead>
    <tbody>
      @foreach($rows as $r)
        <tr><td>{{ $r->d }}</td><td>{{ $r->orders }}</td><td>{{ number_format($r->revenue,2) }}</td></tr>
      @endforeach
    </tbody>
  </table>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('sales'), {
  type: 'bar',
  data: { 
    labels: @json($rows->pluck('d')), 
    datasets: [{ 
      label:'Revenue (Rs)', 
      data:@json($rows->pluck('revenue')),
      backgroundColor: '#6f42c1',
      borderColor: '#5a32a3',
      borderWidth: 1
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
        text: 'Sales Revenue by Date'
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
