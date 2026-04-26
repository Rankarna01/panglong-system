@extends('layouts.app')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Penjualan</h1>
            <p class="text-slate-500 text-sm">Rekap transaksi yang dilakukan oleh Kasir.</p>
        </div>
        <button onclick="window.print()" class="bg-white border text-slate-600 px-5 py-2.5 rounded-xl hover:bg-slate-50 flex gap-2"><i class="fas fa-print mt-1"></i> Cetak</button>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b text-[11px] uppercase text-slate-500">
                    <th class="p-4 text-center w-16">No</th>
                    <th class="p-4">Tanggal Transaksi</th>
                    <th class="p-4">No. Invoice</th>
                    <th class="p-4">Kasir</th>
                    <th class="p-4 text-right">Total Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($sales as $index => $item)
                <tr class="border-b hover:bg-slate-50">
                    <td class="p-4 text-center">{{ $index + 1 }}</td>
                    <td class="p-4">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</td>
                    <td class="p-4 font-bold text-primary">{{ $item->invoice }}</td>
                    <td class="p-4 text-slate-600">{{ $item->user->name ?? '-' }}</td>
                    <td class="p-4 text-right font-bold text-slate-800">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-10 text-center text-slate-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>@media print { aside, header, button { display: none !important; } main { padding: 0 !important; } }</style>
@endsection