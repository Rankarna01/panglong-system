<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $data = [
            'total_products'  => Product::count(),
            'low_stock'       => Product::whereColumn('stock', '<=', 'min_stock')->count(),
            'stock_in_today'  => StockIn::whereDate('date', $today)->sum('qty'),
            'stock_out_today' => StockOut::whereDate('date', $today)->sum('qty'),
        ];

        $chart_dates = collect();
        $chart_in = collect();
        $chart_out = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chart_dates->push(Carbon::parse($date)->format('d M'));
            $chart_in->push(StockIn::whereDate('date', $date)->sum('qty'));
            $chart_out->push(StockOut::whereDate('date', $date)->sum('qty'));
        }
        $recent_activities = StockIn::with(['product', 'supplier'])->latest()->take(5)->get();

        return view('staff.dashboard.index', compact('data', 'chart_dates', 'chart_in', 'chart_out', 'recent_activities'));
    }
}
