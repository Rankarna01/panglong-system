<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StockOut;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockOutController extends Controller
{
    public function index()
    {
    
        $stockOuts = StockOut::with(['product', 'user'])->latest()->get();
        
        $products = Product::where('stock', '>', 0)->get();

        return view('staff.stok-keluar.index', compact('stockOuts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:1',
            'date' => 'required|date',
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);

                // Proteksi: Cek apakah stok cukup
                if ($product->stock < $request->qty) {
                    throw new \Exception("Stok tidak mencukupi! Sisa stok saat ini hanya {$product->stock}.");
                }

                // 1. Generate Nomor Referensi (TRK = Transaksi Keluar)
                $reference = 'TRK-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

                // 2. Simpan Data Stok Keluar
                StockOut::create([
                    'reference' => $reference,
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $request->qty,
                    'date' => $request->date,
                    'reason' => $request->reason,
                ]);

                // 3. Kurangi Stok di Master Data Barang
                $product->decrement('stock', $request->qty);
            });

            return redirect()->back()->with('success', 'Barang keluar berhasil dicatat & stok otomatis berkurang!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $stockOut = StockOut::findOrFail($id);
                
                // Kembalikan stok barang karena riwayat dibatalkan (salah input)
                $product = Product::findOrFail($stockOut->product_id);
                $product->increment('stock', $stockOut->qty);

                // Hapus data riwayat
                $stockOut->delete();
            });

            return redirect()->back()->with('success', 'Riwayat dibatalkan dan stok telah dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }
}