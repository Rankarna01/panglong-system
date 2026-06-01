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

    public function export(\Illuminate\Http\Request $request)
    {
        $sales = Sale::with('user')->latest()->get();
        $title = 'Laporan Penjualan';

        if ($request->format == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.penjualan', compact('sales', 'title'));
            return $pdf->download('laporan-penjualan.pdf');
        }

        return response(view('admin.exports.penjualan', compact('sales', 'title')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="laporan-penjualan.xls"');
    }
}
