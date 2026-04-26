<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleHistoryController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['user', 'details.product'])->latest()->get();

        return view('admin.riwayat-penjualan.index', compact('sales'));
    }
}
