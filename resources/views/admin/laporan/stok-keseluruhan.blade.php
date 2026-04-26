@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Sisa Stok</h1>
            <p class="text-slate-500 text-sm">Rekapitulasi sisa persediaan barang saat ini.</p>
        </div>
        <button onclick="window.print()" class="bg-white border text-slate-600 px-5 py-2.5 rounded-xl hover:bg-slate-50 flex gap-2"><i class="fas fa-print mt-1"></i> Cetak</button>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b text-[11px] uppercase text-slate-500">
                    <th class="p-4 text-center w-16">No</th>
                    <th class="p-4">Nama Barang</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4 text-right">Harga (Rp)</th>
                    <th class="p-4 text-center">Sisa Stok</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($products as $index => $item)
                <tr class="border-b hover:bg-slate-50">
                    <td class="p-4 text-center">{{ $index + 1 }}</td>
                    <td class="p-4 font-bold text-primary">{{ $item->name }}<br><span class="text-xs text-slate-400 font-normal">{{ $item->code }}</span></td>
                    <td class="p-4 text-slate-600">{{ $item->category->name ?? '-' }}</td>
                    <td class="p-4 text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="p-4 text-center font-bold text-lg {{ $item->stock <= $item->min_stock ? 'text-red-500' : 'text-slate-800' }}">
                        {{ $item->stock }} <span class="text-xs font-normal text-slate-500">{{ $item->unit->short_name ?? '' }}</span>
                    </td>
                    <td class="p-4 text-center">
                        @if($item->stock <= 0)
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-[10px] font-bold">KOSONG</span>
                        @elseif($item->stock <= $item->min_stock)
                            <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded text-[10px] font-bold">MENIPIS</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold">AMAN</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>@media print { aside, header, button { display: none !important; } main { padding: 0 !important; } }</style>
@endsection