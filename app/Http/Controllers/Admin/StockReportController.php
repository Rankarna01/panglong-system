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

    public function export(\Illuminate\Http\Request $request)
    {
        $products = Product::with(['category', 'unit'])->orderBy('name', 'ASC')->get();
        $title = 'Laporan Sisa Stok';

        if ($request->format == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.stok', compact('products', 'title'));
            return $pdf->download('laporan-sisa-stok.pdf');
        }

        return response(view('admin.exports.stok', compact('products', 'title')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="laporan-sisa-stok.xls"');
    }
}