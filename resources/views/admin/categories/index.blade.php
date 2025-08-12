@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3 d-flex justify-content-between">
    <span>Categories</span>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-5">Add</a>
  </h1>
  @include('partials.flash')

  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Slug</th>
        <th>Description</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($categories as $c)
      <tr>
        <td>{{ $c->name }}</td>
        <td><code>{{ $c->slug }}</code></td>
        <td>{{ Str::limit($c->description, 100) }}</td>
        <td>
          @if($c->is_active)
            <span class="badge bg-success">Active</span>
          @else
            <span class="badge bg-secondary">Inactive</span>
          @endif
        </td>
        <td class="center">
          <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.categories.edit',$c) }}">Edit</a>
          <form action="{{ route('admin.categories.destroy',$c) }}" method="post" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>


  <!--Pagination-->
  <div class="d-flex justify-content-center mt-5">
      {{ $categories->links('pagination::bootstrap-5') }}
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
@endsection
