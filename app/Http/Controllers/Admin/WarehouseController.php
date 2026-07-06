<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->get();
        return view('admin.gudang.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:warehouses,code',
            'name' => 'required',
        ]);

        Warehouse::create($request->all());
        return redirect()->back()->with('success', 'Master gudang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $request->validate([
            'code' => 'required|unique:warehouses,code,' . $id,
            'name' => 'required',
        ]);

        $warehouse->update($request->all());
        return redirect()->back()->with('success', 'Data gudang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Warehouse::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data gudang berhasil dihapus!');
    }
}