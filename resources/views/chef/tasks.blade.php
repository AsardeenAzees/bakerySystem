@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">What to Bake (Low Stock)</h1>
  @include('partials.flash')
  
  @if($lowStock->count() > 0)
    <div class="card">
      <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
          <i class="fas fa-exclamation-triangle"></i> 
          {{ $lowStock->count() }} items need attention
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>Current Stock</th>
                <th>Reorder Level</th>
                <th>Status</th>
                <th>Add Stock</th>
              </tr>
            </thead>
            <tbody>
              @foreach($lowStock as $i)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    @if($i->product->image)
                      <img src="{{ asset('storage/' . $i->product->image) }}" 
                           class="me-3 rounded" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                      <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center" 
                           style="width: 50px; height: 50px;">
                        <i class="fas fa-birthday-cake text-muted"></i>
                      </div>
                    @endif
                    <div>
                      <strong>{{ $i->product->name }}</strong>
                      <br>
                      <small class="text-muted">{{ $i->product->category->name }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-danger fs-6">{{ $i->quantity }}</span>
                </td>
                <td>
                  <span class="badge bg-secondary">{{ $i->reorder_level }}</span>
                </td>
                <td>
                  @if($i->quantity == 0)
                    <span class="badge bg-danger">Out of Stock</span>
                  @else
                    <span class="badge bg-warning text-dark">Low Stock</span>
                  @endif
                </td>
                <td>
                  <form method="post" action="{{ route('chef.stock.increase',$i->product) }}" class="d-flex gap-2">
                    @csrf
                    <input name="qty" type="number" min="1" value="5" class="form-control" style="max-width:120px">
                    <button class="btn btn-sm btn-primary">
                      <i class="fas fa-plus"></i> Add
                    </button>
                  </form>
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
      <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
      <h3>No low stock items 🎉</h3>
      <p class="text-muted">All products are well stocked. Great job!</p>
    </div>
  @endif
</div>
@endsection
