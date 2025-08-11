<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;

class StripeController extends Controller
{
    public function start(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $currency = config('services.stripe.currency', 'usd');

        $session = \Stripe\Checkout\Session::create([
            'mode'                 => 'payment',
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'quantity'   => 1,
                'price_data' => [
                    'currency'     => $currency,
                    'unit_amount'  => (int) round($order->total * 100), // smallest unit
                    'product_data' => ['name' => 'Order #'.$order->id],
                ],
            ]],
            'success_url' => route('pay.stripe.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}&oid='.$order->id,
            'cancel_url'  => route('pay.stripe.cancel', [], true) . '?oid='.$order->id,
        ]);

        // Save provider_ref on Payment
        $order->payment?->update(['provider_ref' => $session->id]);

        return redirect()->away($session->url);
    }

    public function success()
    {
        $order = Order::findOrFail(request('oid'));
        abort_unless($order->user_id === auth()->id(), 403);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $sessionId = request('session_id');
        $session   = \Stripe\Checkout\Session::retrieve($sessionId);

        if ($session && $session->payment_status === 'paid') {
            $order->payment?->update(['status' => 'succeeded']);
            $order->update(['status' => 'processing']); // move forward after payment
            return redirect()->route('orders.show', $order)->with('success', 'Payment succeeded. Thanks!');
        }

        return redirect()->route('orders.show', $order)->with('error', 'Payment not confirmed.');
    }

    public function cancel()
    {
        $order = Order::findOrFail(request('oid'));
        abort_unless($order->user_id === auth()->id(), 403);

        $order->payment?->update(['status' => 'failed']);
        // optionally: $order->update(['status' => 'cancelled']);
        return redirect()->route('orders.show', $order)->with('error', 'Payment cancelled.');
    }
}
