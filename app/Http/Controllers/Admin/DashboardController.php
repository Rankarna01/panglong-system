<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data untuk Card Stats
        $data = [
            'total_sales_today' => Sale::whereDate('created_at', Carbon::today())->count(),
            'low_stock_count'   => Product::whereColumn('stock', '<=', 'min_stock')->count(),
            'total_products'    => Product::count(),
            'total_users'       => User::count(),
        ];

        // 2. Data untuk Chart (7 Hari Terakhir)
        $chart_data = Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // 3. Aktivitas Terbaru
        $recent_activities = Sale::with('user')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('data', 'chart_data', 'recent_activities'));
    }
}
