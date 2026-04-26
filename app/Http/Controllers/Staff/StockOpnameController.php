<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StockOpname;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::with(['product', 'user'])->latest()->get();
        $products = Product::orderBy('name', 'ASC')->get();

        return view('staff.stok-opname.index', compact('opnames', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'actual_qty' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);
                $system_qty = $product->stock;
                $difference = $request->actual_qty - $system_qty;
                $reference = 'SOP-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

                StockOpname::create([
                    'reference' => $reference,
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'date' => $request->date,
                    'system_qty' => $system_qty,
                    'actual_qty' => $request->actual_qty,
                    'difference' => $difference,
                    'notes' => $request->notes,
                ]);
                $product->update(['stock' => $request->actual_qty]);
            });

            return redirect()->back()->with('success', 'Stok Opname berhasil dicatat & Master Data Barang telah diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal melakukan opname: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $opname = StockOpname::findOrFail($id);
                $product = Product::findOrFail($opname->product_id);
                $product->stock = $product->stock - $opname->difference;
                $product->save();
                $opname->delete();
            });

            return redirect()->back()->with('success', 'Riwayat opname dibatalkan dan stok dikembalikan seperti semula!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal membatalkan riwayat: ' . $e->getMessage());
        }
    }
}
