<?php

namespace App\Http\Controllers;

use App\Models\{Address, Order, OrderItem, Payment, Delivery, Product};
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $items = $this->cart->items();
        if (empty($items)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $subtotal    = $this->cart->subtotal();
        $deliveryFee = 250.00; // simple flat rate
        $discount    = 0.00;
        $total       = $subtotal + $deliveryFee - $discount;

        $addresses = Address::where('user_id', auth()->id())
            ->orderByDesc('is_default')
            ->get();

        return view('checkout.index', compact(
            'items',
            'subtotal',
            'deliveryFee',
            'discount',
            'total',
            'addresses'
        ));
    }

    public function place(Request $request)
    {
        $items = $this->cart->items();
        if (empty($items)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'address_mode'   => 'required|in:existing,new',
            'address_id'     => 'nullable|exists:addresses,id',
            'line1'          => 'required_if:address_mode,new|max:255',
            'city'           => 'required_if:address_mode,new|max:100',
            'state'          => 'nullable|max:100',
            'postal_code'    => 'nullable|max:20',
            'country'        => 'nullable|max:100',
            'payment_method' => 'required|in:cod,stripe',
        ]);

        $deliveryFee = 250.00;
        $discount    = 0.00;
        $subtotal    = $this->cart->subtotal();
        $total       = $subtotal + $deliveryFee - $discount;
        $userId      = auth()->id();

        $order = DB::transaction(function () use ($validated, $userId, $items, $subtotal, $deliveryFee, $discount, $total) {
            // Address
            if ($validated['address_mode'] === 'existing') {
                $address = Address::where('user_id', $userId)
                    ->where('id', $validated['address_id'])
                    ->firstOrFail();
            } else {
                $address = Address::create([
                    'user_id'     => $userId,
                    'line1'       => $validated['line1'],
                    'line2'       => request('line2'),
                    'city'        => $validated['city'],
                    'state'       => $validated['state'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                    'country'     => $validated['country'] ?? 'Sri Lanka',
                    'is_default'  => true,
                ]);
            }

            // Order
            $order = Order::create([
                'user_id'      => $userId,
                'address_id'   => $address->id,
                'status'       => 'pending', // stripe keeps 'pending' until paid; COD moves to 'processing' below
                'subtotal'     => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount'     => $discount,
                'total'        => $total,
            ]);

            // Items + stock
            foreach ($items as $i) {
                $product = Product::with('inventory')->lockForUpdate()->findOrFail($i['id']);
                $stock   = $product->inventory?->quantity ?? 0;

                if ($stock < $i['qty']) {
                    throw new \RuntimeException("Insufficient stock for {$product->name}.");
                }

                $product->inventory->decrement('quantity', $i['qty']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'qty'        => $i['qty'],
                    'unit_price' => $i['price'],
                    'line_total' => $i['price'] * $i['qty'],
                ]);
            }

            // Payment row (pending for both, status will change later)
            Payment::create([
                'order_id' => $order->id,
                'provider' => $validated['payment_method'] === 'stripe' ? 'stripe' : 'cod',
                'status'   => 'pending',
                'amount'   => $total,
            ]);

            // Delivery placeholder
            Delivery::create([
                'order_id'    => $order->id,
                'assigned_to' => null,
                'status'      => 'pending',
            ]);

            // If COD, move order forward immediately
            if ($validated['payment_method'] === 'cod') {
                $order->update(['status' => 'processing']);
            }

            return $order;
        });

        // After transaction
        $this->cart->clear();

        if ($validated['payment_method'] === 'stripe') {
            return redirect()->route('pay.stripe.start', $order);
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order placed! We’ll start baking soon 🍞');
    }
}
