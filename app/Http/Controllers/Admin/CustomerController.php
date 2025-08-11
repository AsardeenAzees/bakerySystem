<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->withMax('orders', 'created_at');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        // Ensure we're only viewing customers
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->load(['addresses', 'orders.orderItems.product']);

        // Get order statistics
        $orderStats = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->sum('total'),
            'last_order' => $customer->orders->max('created_at'),
            'average_order' => $customer->orders->avg('total'),
        ];

        return view('admin.customers.show', compact('customer', 'orderStats'));
    }

    public function toggleStatus(User $customer)
    {
        // Ensure we're only toggling customers
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->update([
            'is_active' => !$customer->is_active
        ]);

        $status = $customer->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.customers.show', $customer)
            ->with('status', "Customer {$status} successfully.");
    }
}
