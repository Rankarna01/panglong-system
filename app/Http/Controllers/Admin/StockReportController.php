<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;

class StockReportController extends Controller
{
    public function index() {
        $products = Product::with(['category', 'unit'])->orderBy('name', 'ASC')->get();
        return view('admin.laporan.stok-keseluruhan', compact('products'));
    }
}