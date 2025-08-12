<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Delivery, Order, User};
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $deliveries = Delivery::with(['order.user', 'order.address', 'assignedUser', 'order.payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function json()
    {
        $deliveries = Delivery::with(['order.user', 'order.address', 'assignedUser', 'order.payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($deliveries);
    }
}


