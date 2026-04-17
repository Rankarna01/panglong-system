<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockOut;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        // Menarik riwayat barang keluar beserta data relasi produk dan usernya
        $stockOuts = StockOut::with(['product', 'user'])->latest()->get();

        return view('admin.stok-keluar.index', compact('stockOuts'));
    }
}