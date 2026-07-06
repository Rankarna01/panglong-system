<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SaleHistoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today();

        $sales = Sale::with('details.product')
                     ->where('user_id', $userId)
                     ->latest()
                     ->get();

      
        $sales_today = Sale::where('user_id', $userId)->whereDate('created_at', $today)->count();
        $revenue_today = Sale::where('user_id', $userId)->whereDate('created_at', $today)->sum('total_amount');
        $chart_dates = collect();
        $chart_totals = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chart_dates->push(Carbon::parse($date)->format('d M'));
            $total = Sale::where('user_id', $userId)
                         ->whereDate('created_at', $date)
                         ->sum('total_amount');
            
            $chart_totals->push($total);
        }

        return view('kasir.riwayat-penjualan.index', compact(
            'sales', 'sales_today', 'revenue_today', 'chart_dates', 'chart_totals'
        ));
    }
}
