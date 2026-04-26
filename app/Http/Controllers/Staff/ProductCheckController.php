<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\StockOpname;
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
        // 1. Tarik Data Barang beserta Lokasi Raknya
        $product = Product::with(['category', 'baseUnit', 'racks'])->findOrFail($id);

        // 2. Kumpulkan Histori Barang Masuk
        $stockIns = StockIn::with('user')->where('product_id', $id)->get()->map(function($item) {
            return [
                'date' => $item->created_at,
                'type' => 'Masuk',
                'reference' => $item->reference,
                'description' => 'Penerimaan dari Supplier',
                'qty_change' => '+' . $item->qty,
                'user' => $item->user->name ?? 'Sistem',
                'color' => 'emerald'
            ];
        });

        // 3. Kumpulkan Histori Barang Keluar (Rusak/Retur/dll)
        $stockOuts = StockOut::with('user')->where('product_id', $id)->get()->map(function($item) {
            return [
                'date' => $item->created_at,
                'type' => 'Keluar',
                'reference' => $item->reference,
                'description' => $item->reason ?? 'Pengeluaran Manual',
                'qty_change' => '-' . $item->qty,
                'user' => $item->user->name ?? 'Sistem',
                'color' => 'red'
            ];
        });

        // 4. Kumpulkan Histori Opname
        $opnames = StockOpname::with('user')->where('product_id', $id)->get()->map(function($item) {
            $prefix = $item->difference > 0 ? '+' : '';
            $color = $item->difference > 0 ? 'emerald' : ($item->difference < 0 ? 'red' : 'slate');
            return [
                'date' => $item->created_at,
                'type' => 'Opname',
                'reference' => $item->reference,
                'description' => 'Penyesuaian Stok. Catatan: ' . ($item->notes ?? '-'),
                'qty_change' => $prefix . $item->difference,
                'user' => $item->user->name ?? 'Sistem',
                'color' => $color
            ];
        });

        // 5. Kumpulkan Histori Penjualan (Kasir POS)
        $sales = SaleDetail::with(['sale.user'])->where('product_id', $id)->get()->map(function($item) {
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
            ->merge($opnames)
            ->merge($sales)
            ->sortByDesc('date');

        return view('staff.cek-barang.show', compact('product', 'history'));
    }
}