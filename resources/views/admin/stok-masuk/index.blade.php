@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Stok Masuk</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau riwayat penerimaan barang dari supplier yang diinput oleh Gudang.</p>
        </div>
        <button onclick="window.print()" class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-medium hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Referensi & Tanggal</th>
                        <th class="p-4 font-semibold">Barang & Supplier</th>
                        <th class="p-4 font-semibold">Diinput Oleh</th>
                        <th class="p-4 font-semibold text-center">Qty Masuk</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($stockIns as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $item->reference }}</p>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</p>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-primary">{{ $item->product->name ?? 'Barang Dihapus' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="fas fa-truck text-[10px] mr-1"></i> {{ $item->supplier->name ?? '-' }}</p>
                            @if($item->notes)
                            <p class="text-[10px] text-slate-400 mt-1 italic">"{{ $item->notes }}"</p>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium"><i class="fas fa-user text-[10px] mr-1"></i> {{ $item->user->name ?? 'Sistem' }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-lg">+ {{ $item->qty }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            Belum ada laporan stok masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection