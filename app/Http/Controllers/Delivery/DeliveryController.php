<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\{Delivery, Order};
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['order.address', 'order.user', 'order.items.product'])
            ->where('assigned_to', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('delivery.list', compact('deliveries'));
    }

    public function json()
    {
        $deliveries = Delivery::with(['order.address', 'order.user', 'order.items.product', 'order.payment'])
            ->where('assigned_to', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($deliveries);
    }

    public function pickupOrder(Request $request, Order $order)
    {
        $delivery = $order->delivery;
        abort_unless($delivery && $delivery->assigned_to === auth()->id(), 403);

        if ($order->status !== 'awaiting_delivery_pickup') {
            return back()->with('error', 'Order is not ready for pickup.');
        }

        $order->update(['status' => 'out_for_delivery']);
        $delivery->update(['status' => 'in_transit']);

        return back()->with('success', 'Order picked up and is now out for delivery.');
    }

    public function markDelivered(Request $request, Order $order)
    {
        $delivery = $order->delivery;
        abort_unless($delivery && $delivery->assigned_to === auth()->id(), 403);

        if ($order->status !== 'out_for_delivery') {
            return back()->with('error', 'Order must be out for delivery before marking as delivered.');
        }

        $delivery->update(['status' => 'delivered', 'delivered_at' => now()]);
        $order->update(['status' => 'delivered']);

        return back()->with('success', 'Order marked as delivered successfully.');
    }

    public function confirmCodPayment(Request $request, Order $order)
    {
        $delivery = $order->delivery;
        abort_unless($delivery && $delivery->assigned_to === auth()->id(), 403);

        if (!$order->payment || $order->payment->provider !== 'cod') {
            return back()->with('error', 'This order is not Cash on Delivery.');
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Confirm delivery before acknowledging payment received.');
        }

        $order->payment->update(['status' => 'succeeded']);

        return back()->with('success', 'COD payment confirmed as received.');
    }
}
