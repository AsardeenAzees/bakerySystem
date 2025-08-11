<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Inventory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $kpis = [
            'orders_today'   => Order::whereDate('created_at',$today)->count(),
            'revenue_today'  => Order::whereDate('created_at',$today)->sum('total'),
            'pending_orders' => Order::whereIn('status',['pending','processing'])->count(),
            'low_stock'      => Inventory::whereColumn('quantity','<','reorder_level')->count(),
        ];

        // last 7 days revenue
        $sales = Order::select(DB::raw('DATE(created_at) d'), DB::raw('SUM(total) amount'))
            ->where('created_at','>=',now()->subDays(7))
            ->groupBy('d')->orderBy('d')->get();

        return view('admin.dashboard', compact('kpis','sales'));
    }
}
