<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rack;
use Illuminate\Http\Request;

class RackController extends Controller
{
    public function index()
    {
        $racks = Rack::latest()->get();
        return view('admin.rak.index', compact('racks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:racks,code',
            'name' => 'required',
        ]);

        Rack::create($request->all());
        return redirect()->back()->with('success', 'Master rak berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $rack = Rack::findOrFail($id);
        $request->validate([
            'code' => 'required|unique:racks,code,' . $id,
            'name' => 'required',
        ]);

        $rack->update($request->all());
        return redirect()->back()->with('success', 'Data rak berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Rack::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data rak berhasil dihapus!');
    }
}