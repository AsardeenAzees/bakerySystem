<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Inventory;

class TaskController extends Controller
{
    public function index()
    {
        $lowStock = Inventory::with('product')
            ->whereColumn('quantity','<','reorder_level')
            ->orderByRaw('(reorder_level - quantity) DESC')
            ->get();

        return view('chef.tasks', compact('lowStock'));
    }
}
