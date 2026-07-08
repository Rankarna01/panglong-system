<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseMonitorController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with(['products' => function($query) {
            $query->wherePivot('stock', '>', 0);
        }])->get();

        $products = Product::with('baseUnit')->where('stock', '>', 0)->get();

        foreach ($products as $prod) {
            $allocatedStock = DB::table('product_warehouse')->where('id_product', $prod->id_product)->sum('stock');
            $prod->unallocated_stock = $prod->stock - $allocatedStock;
        }

        return view('admin.gudang.monitoring', compact('warehouses', 'products'));
    }

    public function allocate(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        
        $request->validate([
            'id_product' => 'required|exists:products,id_product',
            'stock' => 'required|numeric|min:0.1'
        ]);

        $product = Product::findOrFail($request->id_product);
        $allocatedStock = DB::table('product_warehouse')->where('id_product', $product->id_product)->sum('stock');
        $unallocatedStock = $product->stock - $allocatedStock;

        if ($request->stock > $unallocatedStock) {
            return redirect()->back()->with('error', 'Stok tidak cukup untuk dialokasikan! Maksimal: ' . $unallocatedStock);
        }

        $existing = $warehouse->products()->where('product_warehouse.id_product', $product->id_product)->first();

        if ($existing) {
            $warehouse->products()->updateExistingPivot($product->id_product, [
                'stock' => $existing->pivot->stock + $request->stock
            ]);
        } else {
            $warehouse->products()->attach($product->id_product, ['stock' => $request->qty]);
        }

        return redirect()->back()->with('success', 'Berhasil! ' . $request->qty . ' ' . ($product->baseUnit->short_name ?? '') . ' ' . $product->name . ' telah disusun ke dalam ' . $warehouse->name);
    }
}