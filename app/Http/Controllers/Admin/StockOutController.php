<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockOut;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with(['product', 'user'])->latest()->get();
        return view('admin.stok-keluar.index', compact('stockOuts'));
    }

    public function export(Request $request)
    {
        $stockOuts = StockOut::with(['product.baseUnit', 'user'])->latest()->get();
        $title = 'Laporan Stok Keluar';

        if ($request->format == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.stok-keluar', compact('stockOuts', 'title'));
            return $pdf->download('laporan-stok-keluar.pdf');
        }

        return response(view('admin.exports.stok-keluar', compact('stockOuts', 'title')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="laporan-stok-keluar.xls"');
    }
}