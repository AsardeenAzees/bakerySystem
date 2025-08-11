@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">My Addresses</h1>
  @include('partials.flash')

  <div class="row">
    <div class="col-md-7">
      <div class="list-group">
        @forelse($addresses as $a)
          <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-bold">{{ $a->line1 }} {{ $a->line2 }}</div>
                <div class="text-muted">{{ $a->city }}, {{ $a->state }} {{ $a->postal_code }} · {{ $a->country }}</div>
                @if($a->is_default)<span class="badge bg-success mt-2">Default</span>@endif
              </div>
              <div class="text-end">
                <form action="{{ route('addresses.default',$a) }}" method="post" class="d-inline">@csrf
                  <button class="btn btn-sm btn-outline-primary" {{ $a->is_default?'disabled':'' }}>Make Default</button>
                </form>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#edit-{{ $a->id }}">Edit</button>
                <form action="{{ route('addresses.destroy',$a) }}" method="post" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete address?')">Delete</button>
                </form>
              </div>
            </div>
            <div id="edit-{{ $a->id }}" class="collapse mt-3">
              <form method="post" action="{{ route('addresses.update',$a) }}" class="row g-2">
                @csrf @method('PUT')
                <div class="col-md-6"><input name="line1" class="form-control" value="{{ $a->line1 }}" required></div>
                <div class="col-md-6"><input name="line2" class="form-control" value="{{ $a->line2 }}"></div>
                <div class="col-md-4"><input name="city" class="form-control" value="{{ $a->city }}" required></div>
                <div class="col-md-4"><input name="state" class="form-control" value="{{ $a->state }}"></div>
                <div class="col-md-4"><input name="postal_code" class="form-control" value="{{ $a->postal_code }}"></div>
                <div class="col-md-6"><input name="country" class="form-control" value="{{ $a->country }}"></div>
                <div class="col-md-6 text-end"><button class="btn btn-success">Save</button></div>
              </form>
            </div>
          </div>
        @empty
          <div class="alert alert-info">No addresses yet.</div>
        @endforelse
      </div>
    </div>

    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Add New Address</div>
        <div class="card-body">
          <form method="post" action="{{ route('addresses.store') }}" class="row g-2">
            @csrf
            <div class="col-md-12"><input name="line1" class="form-control" placeholder="Line 1" required></div>
            <div class="col-md-12"><input name="line2" class="form-control" placeholder="Line 2"></div>
            <div class="col-md-4"><input name="city" class="form-control" placeholder="City" required></div>
            <div class="col-md-4"><input name="state" class="form-control" placeholder="State"></div>
            <div class="col-md-4"><input name="postal_code" class="form-control" placeholder="Postal code"></div>
            <div class="col-md-6"><input name="country" class="form-control" placeholder="Country" value="Sri Lanka"></div>
            <div class="col-md-6 text-end"><button class="btn btn-primary">Add</button></div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
