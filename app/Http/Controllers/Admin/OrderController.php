<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Delivery, User};
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        
        $orders = Order::with(['user', 'delivery', 'payment', 'items.product'])
            ->when($status && $status !== '', fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $deliveryUsers = User::where('role', 'delivery')->orderBy('name')->get();
        
        return view('admin.orders.index', compact('orders', 'deliveryUsers', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'address', 'items.product', 'delivery.assignedUser', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    public function assignDelivery(Request $request, Order $order)
    {
        $data = $request->validate(['assigned_to' => 'nullable|exists:users,id']);
        $delivery = $order->delivery()->updateOrCreate(
            ['order_id' => $order->id],
            ['assigned_to' => $data['assigned_to'] ?? null, 'status' => $data['assigned_to'] ? 'assigned' : 'pending']
        );

        if ($data['assigned_to']) {
            if ($order->status === 'processing' || $order->status === 'ready') {
                $order->update(['status' => 'awaiting_delivery_pickup']);
            }
        }

        return back()->with('success', 'Delivery assignment updated.');
    }

    public function proceedToDelivery(Order $order)
    {
        if (!$order->canProceedToDelivery()) {
            return back()->with('error', 'Order cannot proceed to delivery in its current status.');
        }

        $order->update(['status' => 'awaiting_delivery_pickup']);
        
        // Create or update delivery record
        $order->delivery()->updateOrCreate(
            ['order_id' => $order->id],
            ['status' => 'pending']
        );

        return back()->with('success', 'Order is now awaiting delivery pickup.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate(['status' => 'required|in:pending,processing,ready,awaiting_delivery_pickup,out_for_delivery,delivered,cancelled']);
        
        $order->update(['status' => $data['status']]);
        
        // Update delivery status accordingly
        if ($data['status'] === 'delivered') {
            $order->delivery()->update(['status' => 'delivered', 'delivered_at' => now()]);
        } elseif ($data['status'] === 'out_for_delivery') {
            $order->delivery()->update(['status' => 'in_transit']);
        } elseif ($data['status'] === 'awaiting_delivery_pickup') {
            $order->delivery()->update(['status' => 'pending']);
        }
        
        return back()->with('success', 'Order status updated successfully.');
    }

    public function refund(Order $order)
    {
        if (!in_array($order->status, ['delivered', 'processing', 'out_for_delivery'])) {
            return back()->with('error', 'This order cannot be refunded.');
        }
        
        $order->update(['status' => 'refunded']);
        $order->payment()->update(['status' => 'refunded']);
        
        // Restore inventory
        foreach ($order->items as $item) {
            if ($item->product->inventory) {
                $item->product->inventory->increment('quantity', $item->qty);
            }
        }
        
        return back()->with('success', 'Order refunded successfully.');
    }
}
