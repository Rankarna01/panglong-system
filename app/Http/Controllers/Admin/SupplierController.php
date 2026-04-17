<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        Supplier::create($request->all());
        return redirect()->back()->with('success', 'Data Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        Supplier::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Data Supplier berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data Supplier berhasil dihapus!');
    }
}
