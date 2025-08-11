@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Customer Details: {{ $customer->name }}
                    </h4>
                    <div>
                        <form method="POST" action="{{ route('admin.customers.toggleStatus', $customer) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $customer->is_active ? 'warning' : 'success' }} btn-sm">
                                <i class="fas fa-{{ $customer->is_active ? 'ban' : 'check' }} me-1"></i>
                                {{ $customer->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm ms-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="card-title">
                                <i class="fas fa-user-circle me-2"></i>
                                Profile Information
                            </h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($customer->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Joined:</strong></td>
                                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">
                                <i class="fas fa-chart-bar me-2"></i>
                                Order Statistics
                            </h5>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h3>{{ $orderStats['total_orders'] }}</h3>
                                            <small>Total Orders</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h3>${{ number_format($orderStats['total_spent'], 2) }}</h3>
                                            <small>Total Spent</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h3>${{ number_format($orderStats['average_order'], 2) }}</h3>
                                            <small>Average Order</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h3>{{ $orderStats['last_order'] ? \Illuminate\Support\Carbon::parse($orderStats['last_order'])->format('M d') : 'Never' }}</h3>
                                            <small>Last Order</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Addresses -->
                    @if($customer->addresses->count() > 0)
                    <div class="mb-4">
                        <h5 class="card-title">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Addresses ({{ $customer->addresses->count() }})
                        </h5>
                        <div class="row">
                            @foreach($customer->addresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            {{ $address->type }}
                                            @if($address->is_default)
                                                <span class="badge bg-primary">Default</span>
                                            @endif
                                        </h6>
                                        <p class="card-text mb-1">{{ $address->street }}</p>
                                        <p class="card-text mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                                        <p class="card-text text-muted">{{ $address->country }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Order History -->
                    <div>
                        <h5 class="card-title">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Order History
                        </h5>
                        @if($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->id }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="badge bg-info">Confirmed</span>
                                                    @break
                                                @case('preparing')
                                                    <span class="badge bg-primary">Preparing</span>
                                                    @break
                                                @case('ready')
                                                    <span class="badge bg-success">Ready</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($order->payment_status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                                <p>No orders found for this customer.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
