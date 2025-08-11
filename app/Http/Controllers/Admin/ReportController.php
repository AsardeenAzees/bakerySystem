<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->date('from') ?? now()->subDays(30);
        $to   = $request->date('to') ?? now();

        $rows = Order::select(DB::raw('DATE(created_at) d'), DB::raw('COUNT(*) orders'), DB::raw('SUM(total) revenue'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('d')->orderBy('d')->get();

        return view('admin.reports.sales', compact('rows','from','to'));
    }

    public function stock()
    {
        $rows = Inventory::with('product.category')->orderBy('quantity')->get();
        return view('admin.reports.stock', compact('rows'));
    }
}
