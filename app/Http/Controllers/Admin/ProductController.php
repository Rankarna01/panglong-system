<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\UnitConversion; // Pastikan model ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'unit', 'conversions.unit'])->latest()->get();
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'code' => 'BRG-' . strtoupper(Str::random(5)),
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'image' => $imagePath, 
            'stock' => $request->stock,
            'min_stock' => $request->min_stock ?? 5,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'image' => $imagePath, 
            'stock' => $request->stock,
            'min_stock' => $request->min_stock ?? 5,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Data barang berhasil diperbarui!');
    }

  public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus file fisik foto jika barang dihapus
        if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->back()->with('success', 'Data barang berhasil dihapus!');
    }

    
    public function storeConversion(Request $request, $productId)
    {
        $request->validate([
            'unit_id' => 'required',
            'multiplier' => 'required|numeric|min:0.1'
        ]);

        UnitConversion::create([
            'product_id' => $productId,
            'unit_id' => $request->unit_id,
            'multiplier' => $request->multiplier
        ]);

        return redirect()->back()->with('success', 'Rumus konversi satuan berhasil ditambahkan!');
    }

    public function destroyConversion($id)
    {
        UnitConversion::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Konversi satuan dihapus!');
    }
}