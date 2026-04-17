<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit'])->latest()->get();
        $categories = Category::all();
        $units = Unit::all();

        return view('admin.produk.index', compact('products', 'categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        Product::create([
            'code' => 'BRG-' . strtoupper(Str::random(5)), // Generate Kode Otomatis
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock ?? 5,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock ?? 5,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data barang berhasil dihapus!');
    }
}
