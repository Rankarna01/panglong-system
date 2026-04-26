<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rack;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RackMonitorController extends Controller
{
    public function index()
    {
        $racks = Rack::with(['products' => function($query) {
            $query->wherePivot('stock', '>', 0);
        }])->get();

        $products = Product::with('baseUnit')->where('stock', '>', 0)->get();
        $unallocatedProducts = collect();

        foreach ($products as $prod) {
            $allocatedStock = DB::table('product_rack')->where('product_id', $prod->id)->sum('stock');
            $unallocatedStock = $prod->stock - $allocatedStock;

            if ($unallocatedStock > 0) {
                $prod->unallocated_qty = $unallocatedStock;
                $unallocatedProducts->push($prod);
            }
        }

        return view('admin.rak.monitoring', compact('racks', 'unallocatedProducts'));
    }

    public function allocate(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:0.1'
        ]);

        $rack = Rack::findOrFail($id);
        $product = Product::findOrFail($request->product_id);
        $allocatedStock = DB::table('product_rack')->where('product_id', $product->id)->sum('stock');
        $unallocatedStock = $product->stock - $allocatedStock;

        if ($request->qty > $unallocatedStock) {
            return redirect()->back()->withErrors('Jumlah melebihi stok yang ada di Area Transit! Sisa maksimal: ' . $unallocatedStock);
        }

        $existing = $rack->products()->where('product_id', $product->id)->first();

        if ($existing) {
            $rack->products()->updateExistingPivot($product->id, [
                'stock' => $existing->pivot->stock + $request->qty
            ]);
        } else {
            $rack->products()->attach($product->id, ['stock' => $request->qty]);
        }

        return redirect()->back()->with('success', 'Berhasil! ' . $request->qty . ' ' . ($product->baseUnit->short_name ?? '') . ' ' . $product->name . ' telah disusun ke dalam ' . $rack->name);
    }
}