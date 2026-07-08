<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\SaleDetail;
use Illuminate\Http\Request;

class ProductCheckController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan daftar barang untuk dicari staff
        $query = Product::with(['category', 'baseUnit']);
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        $products = $query->paginate(12);
        
        return view('staff.cek-barang.index', compact('products'));
    }

    public function show($id)
    {
        // 1. Tarik Data Barang beserta Lokasi Gudangnya
        $product = Product::with(['category', 'baseUnit', 'warehouses'])->findOrFail($id);

        // 2. Kumpulkan Histori Barang Masuk
        $stockIns = StockIn::with('user')->where('product_id', $id)->get()->map(function($item) {
            return [
                'date' => $item->created_at,
                'type' => 'Masuk',
                'reference' => $item->reference,
                'description' => 'Penerimaan dari Supplier',
                'qty_change' => '+' . $item->qty,
        $stockIns = StockIn::with('user')->where('id_product', $id)->get()->map(function($item) {
            $item->type = 'in';
            $item->desc = 'Pembelian dari Supplier (' . ($item->supplier->name ?? '-') . ')';
            return $item;
        });

        $stockOuts = StockOut::with('user')->where('id_product', $id)->get()->map(function($item) {
            $item->type = 'out';
            $item->desc = 'Barang Keluar: ' . $item->reason;
            return $item;
        });

        // 2) Ambil data Penjualan (Kasir)
        // Kita butuh relasi ke Sale -> User (Kasir)
        $sales = SaleDetail::with(['sale.user'])->where('id_product', $id)->get()->map(function($item) {
            return [
                'date' => $item->created_at,
                'type' => 'Terjual',
                'reference' => $item->sale->invoice ?? '-',
                'description' => 'Penjualan via Kasir POS',
                'qty_change' => '-' . $item->qty,
                'user' => $item->sale->user->name ?? 'Sistem',
                'color' => 'blue'
            ];
        });

        // 6. Gabungkan semua histori, lalu urutkan dari yang terbaru (sortByDesc)
        $history = collect()
            ->merge($stockIns)
            ->merge($stockOuts)
            ->merge($sales)
            ->sortByDesc('date');

        return view('staff.cek-barang.show', compact('product', 'history'));
    }
}