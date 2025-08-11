@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">Stock Report</h1>
  
  <div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Inventory Overview</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="text-center p-3 bg-primary text-white rounded">
            <h4 class="mb-1">{{ $rows->count() }}</h4>
            <small>Total Products</small>
          </div>
        </div>
        <div class="col-md-3">
          <div class="text-center p-3 bg-warning text-dark rounded">
            <h4 class="mb-1">{{ $rows->where('quantity', '<', 'reorder_level')->count() }}</h4>
            <small>Low Stock Items</small>
          </div>
        </div>
        <div class="col-md-3">
          <div class="text-center p-3 bg-success text-white rounded">
            <h4 class="mb-1">{{ $rows->where('quantity', '>', 'reorder_level')->count() }}</h4>
            <small>Well Stocked</small>
          </div>
        </div>
        <div class="col-md-3">
          <div class="text-center p-3 bg-danger text-white rounded">
            <h4 class="mb-1">{{ $rows->where('quantity', '=', 0)->count() }}</h4>
            <small>Out of Stock</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Product Stock Details</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th>Category</th>
              <th>Current Stock</th>
              <th>Reorder Level</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $r)
            @php($low = $r->quantity < $r->reorder_level)
            <tr class="{{ $low ? 'table-warning' : '' }}">
              <td>
                <strong>{{ $r->product->name }}</strong>
                @if($r->product->image)
                  <img src="{{ asset('storage/' . $r->product->image) }}" 
                       class="ms-2 rounded" style="width: 30px; height: 30px; object-fit: cover;">
                @endif
              </td>
              <td>
                <span class="badge bg-secondary">{{ $r->product->category->name }}</span>
              </td>
              <td>
                <span class="fw-bold {{ $r->quantity == 0 ? 'text-danger' : ($low ? 'text-warning' : 'text-success') }}">
                  {{ $r->quantity }}
                </span>
              </td>
              <td>{{ $r->reorder_level }}</td>
              <td>
                @if($r->quantity == 0)
                  <span class="badge bg-danger">Out of Stock</span>
                @elseif($low)
                  <span class="badge bg-warning text-dark">Low Stock</span>
                @else
                  <span class="badge bg-success">Well Stocked</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
