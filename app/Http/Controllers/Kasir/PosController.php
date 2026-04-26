<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; 

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'baseUnit', 'conversions.unit'])
                           ->where('stock', '>', 0)
                           ->get();
        $categories = Category::all();

        return view('kasir.pos.index', compact('products', 'categories'));
    }

  public function store(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|string',
            'total_amount' => 'required|numeric|min:1',
            'cash_given' => 'required|numeric'
        ]);

        $cart = json_decode($request->cart_data, true);

        if (empty($cart)) {
            return redirect()->back()->withErrors('Keranjang belanja masih kosong!');
        }

        try {
            $sale = DB::transaction(function () use ($request, $cart) {
                $invoice = 'INV-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

                $newSale = Sale::create([
                    'invoice' => $invoice,
                    'user_id' => Auth::id(),
                    'total_amount' => $request->total_amount,
                ]);

                session(['cash_given' => $request->cash_given]);

                foreach ($cart as $item) {
                    $product = Product::findOrFail($item['id']);
                    $realQtyToDeduct = $item['qty_in_base'];

                    if ($product->stock < $realQtyToDeduct) {
                        throw new \Exception("Stok {$product->name} tidak cukup! Sisa global: {$product->stock}");
                    }

                    // 1. Buat Data Detail Penjualan
                    SaleDetail::create([
                        'sale_id' => $newSale->id,
                        'product_id' => $product->id,
                        'qty' => $realQtyToDeduct, 
                        'price' => $product->price, 
                        'subtotal' => $item['subtotal'], 
                    ]);

                    // 2. Kurangi Stok Global (Tabel Products)
                    $product->decrement('stock', $realQtyToDeduct);

                    // ========================================================
                    // 3. LOGIKA WMS: AUTO-DEDUCT DARI RAK (METODE FIFO)
                    // ========================================================
                    $qtyLeftToDeductFromRack = $realQtyToDeduct;
                    $productRacks = DB::table('product_rack')
                        ->where('product_id', $product->id)
                        ->where('stock', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach ($productRacks as $pr) {
                        if ($qtyLeftToDeductFromRack <= 0) break; 

                        if ($pr->stock >= $qtyLeftToDeductFromRack) {
                            DB::table('product_rack')
                                ->where('id', $pr->id)
                                ->decrement('stock', $qtyLeftToDeductFromRack);
                            
                            $qtyLeftToDeductFromRack = 0; // Lunas
                        } else {
                            $qtyLeftToDeductFromRack -= $pr->stock;
                            
                            DB::table('product_rack')
                                ->where('id', $pr->id)
                                ->update(['stock' => 0]);
                        }
                    }
                }

                return $newSale;
            });

            return redirect()->back()->with('success', 'Transaksi Berhasil!')->with('print_invoice', $sale->id);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Transaksi Gagal: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $sale = Sale::with(['details.product.baseUnit', 'user'])->findOrFail($id);
        
        $customPaper = array(0, 0, 226.77, 800); 
        $pdf = Pdf::loadView('kasir.pos.print', compact('sale'))
                  ->setPaper($customPaper, 'portrait');

        return $pdf->stream('Struk-' . $sale->invoice . '.pdf');
    }
}