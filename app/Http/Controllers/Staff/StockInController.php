<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StockIn;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\UnitConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with(['product.baseUnit', 'supplier', 'user'])->latest()->get();
        $products = Product::with(['baseUnit', 'conversions.unit'])->orderBy('name', 'ASC')->get();
        $suppliers = Supplier::all();

        return view('staff.stok-masuk.index', compact('stockIns', 'products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'product_id' => 'required',
            'unit_id' => 'required', 
            'input_qty' => 'required|numeric|min:0.1', 
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = Product::with('baseUnit')->findOrFail($request->product_id);
                
                $multiplier = 1;
                $unitName = $product->baseUnit->name ?? 'Satuan';
                if ($request->unit_id != $product->unit_id) {
                    $conversion = UnitConversion::with('unit')
                        ->where('product_id', $product->id)
                        ->where('unit_id', $request->unit_id)
                        ->first();

                    if (!$conversion) {
                        throw new \Exception("Aturan konversi satuan tidak ditemukan!");
                    }
                    $multiplier = $conversion->multiplier;
                    $unitName = $conversion->unit->name;
                }

                $realQty = $request->input_qty * $multiplier;
                $historyNote = "Input: " . $request->input_qty . " " . $unitName;
                $finalNotes = $request->notes ? $request->notes . " | " . $historyNote : $historyNote;
                $reference = 'TRM-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

                StockIn::create([
                    'reference' => $reference,
                    'supplier_id' => $request->supplier_id,
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'qty' => $realQty, 
                    'date' => $request->date,
                    'payment_method' => $request->payment_method,
                    'notes' => $finalNotes,
                ]);
                $product->increment('stock', $realQty);
            });

            return redirect()->back()->with('success', 'Penerimaan berhasil! Stok otomatis dikonversi ke satuan dasar.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal mencatat stok: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required',
            'product_id' => 'required',
            'unit_id' => 'required',
            'input_qty' => 'required|numeric|min:0.1',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $stockIn = StockIn::findOrFail($id);
                $oldProduct = Product::findOrFail($stockIn->product_id);
                
                // Revert stok lama
                $oldProduct->decrement('stock', $stockIn->qty);

                // Proses stok baru
                $newProduct = Product::with('baseUnit')->findOrFail($request->product_id);
                
                $multiplier = 1;
                $unitName = $newProduct->baseUnit->name ?? 'Satuan';
                if ($request->unit_id != $newProduct->unit_id) {
                    $conversion = UnitConversion::with('unit')
                        ->where('product_id', $newProduct->id)
                        ->where('unit_id', $request->unit_id)
                        ->first();

                    if (!$conversion) {
                        throw new \Exception("Aturan konversi satuan tidak ditemukan!");
                    }
                    $multiplier = $conversion->multiplier;
                    $unitName = $conversion->unit->name;
                }

                $realQty = $request->input_qty * $multiplier;
                $historyNote = "Input: " . $request->input_qty . " " . $unitName;
                $finalNotes = $request->notes ? $request->notes . " | " . $historyNote : $historyNote;

                $stockIn->update([
                    'supplier_id' => $request->supplier_id,
                    'product_id' => $request->product_id,
                    'qty' => $realQty,
                    'date' => $request->date,
                    'payment_method' => $request->payment_method,
                    'notes' => $finalNotes,
                ]);

                // Tambahkan stok baru
                $newProduct->increment('stock', $realQty);
            });

            return redirect()->back()->with('success', 'Penerimaan berhasil diupdate! Stok otomatis disesuaikan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal mengupdate stok: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $stockIn = StockIn::findOrFail($id);
                $product = Product::findOrFail($stockIn->product_id);
                $product->decrement('stock', $stockIn->qty);
                $stockIn->delete();
            });

            return redirect()->back()->with('success', 'Riwayat berhasil dibatalkan dan stok dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }
}