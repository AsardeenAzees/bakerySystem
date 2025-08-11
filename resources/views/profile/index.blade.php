@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Profile Management
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Profile Information Form -->
                    <div class="mb-4">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-user-edit me-2"></i>
                            Profile Information
                        </h5>
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <hr>

                    <!-- Password Update Form -->
                    <div class="mb-4">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-key me-2"></i>
                            Update Password
                        </h5>
                        @include('profile.partials.update-password-form')
                    </div>

                    <hr>

                    <!-- Delete Account Form -->
                    <div class="mb-4">
                        <h5 class="card-title mb-3 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Delete Account
                        </h5>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
