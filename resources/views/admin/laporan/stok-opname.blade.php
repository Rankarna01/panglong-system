@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Stok Opname</h1>
            <p class="text-slate-500 text-sm">Penyesuaian stok sistem vs fisik oleh gudang.</p>
        </div>
        <button onclick="window.print()" class="bg-white border text-slate-600 px-5 py-2.5 rounded-xl hover:bg-slate-50 flex gap-2"><i class="fas fa-print mt-1"></i> Cetak</button>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden print:border-none print:shadow-none">
        <table class="w-full text-left border-collapse print:text-xs">
            <thead>
                <tr class="bg-slate-50 border-b text-[11px] uppercase text-slate-500">
                    <th class="p-4 w-16 text-center">No</th>
                    <th class="p-4">Tanggal & Ref</th>
                    <th class="p-4">Barang</th>
                    <th class="p-4 text-center">Sistem</th>
                    <th class="p-4 text-center">Fisik</th>
                    <th class="p-4 text-center">Selisih</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($opnames as $index => $item)
                <tr class="border-b hover:bg-slate-50">
                    <td class="p-4 text-center">{{ $index + 1 }}</td>
                    <td class="p-4">
                        <b>{{ $item->reference }}</b><br>
                        <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</span>
                    </td>
                    <td class="p-4 text-primary font-bold">{{ $item->product->name }}</td>
                    <td class="p-4 text-center">{{ $item->system_qty }}</td>
                    <td class="p-4 text-center font-bold text-slate-800">{{ $item->actual_qty }}</td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 rounded font-bold text-xs {{ $item->difference < 0 ? 'bg-red-100 text-red-600' : ($item->difference > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-600') }}">
                            {{ $item->difference > 0 ? '+'.$item->difference : $item->difference }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>@media print { aside, header, button { display: none !important; } main { padding: 0 !important; } }</style>
@endsection