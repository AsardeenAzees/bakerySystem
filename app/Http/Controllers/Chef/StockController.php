<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function increase(Request $request, Product $product)
    {
        $data = $request->validate(['qty'=>'required|integer|min:1']);
        $product->inventory()->increment('quantity', $data['qty']);
        return back()->with('success','Stock increased.');
    }
}
