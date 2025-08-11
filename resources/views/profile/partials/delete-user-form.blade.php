<form method="post" action="{{ route('profile.destroy') }}" class="mt-3" 
      onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
    @csrf
    @method('delete')

    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Warning:</strong> Once your account is deleted, all of its resources and data will be permanently deleted.
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" placeholder="Enter your password to confirm" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash me-1"></i>
            Delete Account
        </button>
    </div>
</form>
