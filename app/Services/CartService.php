<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    public function items(): array
    {
        return session('cart', []);
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = session('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $quantity;
        } else {
            $product = Product::findOrFail($productId);
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->effective_price,
                'image' => $product->image,
                'qty' => $quantity,
            ];
        }
        
        session(['cart' => $cart]);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = session('cart', []);
        
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['qty'] = $quantity;
            }
            session(['cart' => $cart]);
        }
    }

    public function remove(int $productId): void
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function subtotal(): float
    {
        $cart = session('cart', []);
        return collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });
    }

    public function count(): int
    {
        $cart = session('cart', []);
        return collect($cart)->sum('qty');
    }

    public function hasItems(): bool
    {
        return !empty(session('cart', []));
    }
}
