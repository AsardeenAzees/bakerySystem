<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create() { return view('admin.categories.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable|max:500',
            'is_active' => 'boolean'
        ]);
        
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success','Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable|max:500',
            'is_active' => 'boolean'
        ]);
        
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success','Updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success','Deleted.');
    }
}
