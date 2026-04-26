<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Sale;

class SalesReportController extends Controller
{
    public function index() {
        $sales = Sale::with('user')->latest()->get();
        return view('admin.laporan.penjualan', compact('sales'));
    }
}
