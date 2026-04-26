<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\StockOpname;

class StockOpnameController extends Controller
{
    public function index() {
        $opnames = StockOpname::with(['product', 'user'])->latest()->get();
        return view('admin.laporan.stok-opname', compact('opnames'));
    }
}