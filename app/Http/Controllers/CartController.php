<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        
        return view('cart.index', compact('items', 'subtotal'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $this->cart->add($validated['product_id'], $validated['quantity']);

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $this->cart->update($validated['product_id'], $validated['quantity']);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $this->cart->remove($validated['product_id']);

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        $this->cart->clear();

        return redirect()->back()->with('success', 'Cart cleared!');
    }
}
