@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3">New Category</h1>
  @include('partials.flash')
  <form method="post" action="{{ route('admin.categories.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" value="{{ old('name') }}" required>
      @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
      @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
          Active
        </label>
      </div>
    </div>
    <button class="btn btn-success">Create</button>
    <a class="btn btn-secondary" href="{{ route('admin.categories.index') }}">Cancel</a>
  </form>
</div>
@endsection
