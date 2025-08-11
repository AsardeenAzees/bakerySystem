<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Inventory};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category','inventory')->orderBy('created_at','desc')->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', ['categories'=>Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|max:150',
            'description' => 'nullable|max:2000',
            'price'       => 'required|numeric|min:0',
            'discount_price'=>'nullable|numeric|min:0|lte:price',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:2048',
            'quantity'    => 'required|integer|min:0',
            'reorder_level'=>'required|integer|min:0',
        ]);

        $product = new Product();
        $product->category_id = $data['category_id'];
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->discount_price = $data['discount_price'];
        $product->slug = Str::slug($data['name']).'-'.Str::random(5);
        $product->is_active = (bool)($request->boolean('is_active', true));

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products','public');
        }
        $product->save();

        Inventory::create([
            'product_id'=>$product->id,
            'quantity'=>$data['quantity'],
            'reorder_level'=>$data['reorder_level'],
        ]);

        return redirect()->route('admin.products.index')->with('success','Product created.');
    }

    public function edit(Product $product)
    {
        $product->load('inventory');
        return view('admin.products.edit', [
            'product'=>$product,
            'categories'=>Category::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|max:150',
            'description' => 'nullable|max:2000',
            'price'       => 'required|numeric|min:0',
            'discount_price'=>'nullable|numeric|min:0|lte:price',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:2048',
            'quantity'    => 'required|integer|min:0',
            'reorder_level'=>'required|integer|min:0',
        ]);

        $product->category_id = $data['category_id'];
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->discount_price = $data['discount_price'];
        $product->is_active = (bool)($request->boolean('is_active', true));

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('products','public');
        }
        $product->save();

        $product->inventory()->updateOrCreate(
            ['product_id'=>$product->id],
            ['quantity'=>$data['quantity'],'reorder_level'=>$data['reorder_level']]
        );

        return back()->with('success','Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return back()->with('success','Product deleted.');
    }
}
