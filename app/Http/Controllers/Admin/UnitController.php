<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->get();
        return view('admin.satuan.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50'
        ]);
        Unit::create($request->only(['name', 'short_name']));
        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50'
        ]);
        Unit::findOrFail($id)->update($request->only(['name', 'short_name']));
        return redirect()->back()->with('success', 'Satuan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Unit::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Satuan berhasil dihapus!');
    }
}
