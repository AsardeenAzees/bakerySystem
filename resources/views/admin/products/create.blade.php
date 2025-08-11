@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">New Product</h1>
  @include('partials.flash')

  <form method="post" enctype="multipart/form-data" action="{{ route('admin.products.store') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
          @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected(old('is_active', true))>Yes</option>
          <option value="0" @selected(!old('is_active', true))>No</option>
        </select>
      </div>
      <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
      </div>
      <div class="col-md-3">
        <label class="form-label">Price (Rs)</label>
        <input name="price" type="number" step="0.01" min="0" class="form-control" value="{{ old('price', 0) }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Discount Price (Rs)</label>
        <input name="discount_price" type="number" step="0.01" min="0" class="form-control" value="{{ old('discount_price') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Quantity</label>
        <input name="quantity" type="number" min="0" class="form-control" value="{{ old('quantity', 0) }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Reorder Level</label>
        <input name="reorder_level" type="number" min="0" class="form-control" value="{{ old('reorder_level', 5) }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
      </div>
    </div>
    <div class="mt-3">
      <button class="btn btn-success">Create</button>
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection
