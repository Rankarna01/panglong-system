<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Discount;
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
        $discounts = Discount::where('is_active', true)->get();

        return view('kasir.pos.index', compact('products', 'categories', 'discounts'));
    }

  public function store(Request $request)
    {
        $request->validate([
            'cart_data' => 'required|string',
            'total_amount' => 'required|numeric|min:1',
            'cash_given' => 'required|numeric',
            'discount_name' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'subtotal' => 'nullable|numeric'
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
                    'id_user' => Auth::id(),
                    'total_amount' => $request->total_amount,
                    'subtotal' => $request->subtotal ?? $request->total_amount,
                    'discount_name' => $request->discount_name,
                    'discount_amount' => $request->discount_amount ?? 0,
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
                        'id_sale' => $newSale->id_sale,
                        'id_product' => $product->id_product,
                        'qty' => $realQtyToDeduct,
                        'price' => $product->price,
                        'subtotal' => $item['subtotal']
                    ]);

                    // 2. Kurangi Stok Global (Tabel Products)
                    $product->decrement('stock', $realQtyToDeduct);

                    // ========================================================
                    // 3. LOGIKA WMS: AUTO-DEDUCT DARI GUDANG (METODE FIFO)
                    // ========================================================
                    $qtyLeftToDeductFromWarehouse = $realQtyToDeduct;
                    $allocations = DB::table('product_warehouse')
                        ->where('id_product', $product->id_product)
                        ->where('stock', '>', 0)
                        ->orderBy('id', 'asc') // FIFO by allocation ID
                        ->get();

                    foreach ($allocations as $pw) {
                        if ($qtyLeftToDeductFromWarehouse <= 0) break; 

                        if ($pw->stock >= $qtyLeftToDeductFromWarehouse) {
                            DB::table('product_warehouse')
                                ->where('id', $pw->id)
                                ->decrement('stock', $qtyLeftToDeductFromWarehouse);
                            
                            $qtyLeftToDeductFromWarehouse = 0; // Lunas
                        } else {
                            $qtyLeftToDeductFromWarehouse -= $pw->stock;
                            
                            DB::table('product_warehouse')
                                ->where('id', $pw->id)
                                ->update(['stock' => 0]);
                        }
                    }
                }

                return $newSale;
            });
            DB::commit();

            return redirect()->back()->with('success', 'Transaksi Berhasil!')->with('print_invoice', $sale->id_sale);
        } catch (\Exception $e) {
            DB::rollBack();
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