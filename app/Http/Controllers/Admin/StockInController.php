<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockIn;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with(['product', 'supplier', 'user'])->latest()->get();
        return view('admin.stok-masuk.index', compact('stockIns'));
    }

    public function export(Request $request)
    {
        $stockIns = StockIn::with(['product.baseUnit', 'supplier', 'user'])->latest()->get();
        $title = 'Laporan Stok Masuk';

        if ($request->format == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.stok-masuk', compact('stockIns', 'title'));
            return $pdf->download('laporan-stok-masuk.pdf');
        }

        return response(view('admin.exports.stok-masuk', compact('stockIns', 'title')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="laporan-stok-masuk.xls"');
    }
}