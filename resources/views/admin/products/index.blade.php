@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3 d-flex justify-content-between">
    <span>Products</span>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add</a>
  </h1>
  @include('partials.flash')

  <div class="row">
    @foreach($products as $p)
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          @if($p->image)
            <img src="{{ asset('storage/'.$p->image) }}" class="card-img-top" alt="{{ $p->name }}" style="height: 200px; object-fit: cover;">
          @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
              <i class="fas fa-birthday-cake fa-3x text-muted"></i>
            </div>
          @endif
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $p->name }}</h5>
            <div class="small text-muted mb-2">{{ $p->category->name }}</div>
            @if($p->description)
              <p class="card-text small text-muted">{{ Str::limit($p->description, 100) }}</p>
            @endif
            <div class="mt-auto">
              <div class="mb-1">
                @if($p->discount_price)
                  <span class="text-primary fw-bold">Rs {{ number_format($p->discount_price, 2) }}</span>
                  <small class="text-muted text-decoration-line-through">Rs {{ number_format($p->price, 2) }}</small>
                @else
                  <span class="text-primary fw-bold">Rs {{ number_format($p->price, 2) }}</span>
                @endif
              </div>
              <div class="small text-muted">
                Stock: {{ $p->inventory->quantity ?? 0 }}
                @if($p->inventory && $p->inventory->quantity <= $p->inventory->reorder_level)
                  <span class="badge bg-warning text-dark">Low Stock</span>
                @endif
              </div>
              <div class="small text-muted">
                Status: 
                @if($p->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </div>
            </div>
          </div>
          <div class="card-footer d-flex gap-2">
            <a class="btn btn-sm btn-outline-secondary w-50" href="{{ route('admin.products.edit',$p) }}">Edit</a>
            <form class="w-50" action="{{ route('admin.products.destroy',$p) }}" method="post">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Delete?')">Delete</button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{ $products->links() }}
</div>
@endsection
