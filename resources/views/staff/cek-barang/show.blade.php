@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-10">
    
    <div class="flex items-center gap-3">
        <a href="{{ route('gudang.cek-barang.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-primary transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail & Kartu Stok</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-32 h-32 mx-auto bg-slate-100 rounded-2xl mb-4 flex items-center justify-center overflow-hidden border border-slate-200 shadow-inner">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-box text-4xl text-slate-300"></i>
                    @endif
                </div>
                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-mono font-bold mb-3">{{ $product->code }}</span>
                <h2 class="text-lg font-bold text-slate-800 leading-tight mb-1">{{ $product->name }}</h2>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-6">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                
                <div class="p-4 bg-primary/5 border border-primary/10 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Stok Global Saat Ini</p>
                    <h3 class="text-3xl font-extrabold text-primary">
                        {{ fmod($product->stock, 1) !== 0.00 ? rtrim(rtrim($product->stock, '0'), '.') : number_format($product->stock, 0) }} 
                        <span class="text-sm font-normal">{{ $product->baseUnit->name ?? '' }}</span>
                    </h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-surface">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-map-marker-alt text-primary"></i> Lokasi Penyimpanan</h3>
                </div>
                <div class="p-4 space-y-3">
                    @php
                        $allocatedStock = 0;
                    @endphp
                    
                    @forelse($product->racks as $rack)
                        @php
                            $allocatedStock += $rack->pivot->stock;
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $rack->name }}</p>
                                <p class="text-[10px] font-mono text-slate-500">{{ $rack->code }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg">{{ fmod($rack->pivot->stock, 1) !== 0.00 ? rtrim(rtrim($rack->pivot->stock, '0'), '.') : number_format($rack->pivot->stock, 0) }} Qty</span>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-circle text-orange-400 text-2xl mb-2"></i>
                            <p class="text-xs text-slate-500">Barang ini belum dialokasikan ke rak manapun.</p>
                        </div>
                    @endforelse

                    @php
                        $unallocated = $product->stock - $allocatedStock;
                    @endphp
                    
                    @if($unallocated > 0)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-orange-200 bg-orange-50 mt-2">
                            <div>
                                <p class="text-sm font-bold text-orange-800">Area Transit / Belum Disusun</p>
                                <p class="text-[10px] text-orange-600">Menunggu dialokasikan ke rak</p>
                            </div>
                            <span class="px-2.5 py-1 bg-orange-500 text-white text-xs font-bold rounded-lg">{{ fmod($unallocated, 1) !== 0.00 ? rtrim(rtrim($unallocated, '0'), '.') : number_format($unallocated, 0) }} Qty</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-[700px]">
            <div class="p-5 border-b border-slate-100 bg-surface flex justify-between items-center shrink-0">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2"><i class="fas fa-history text-primary"></i> Jejak Histori Stok</h3>
                <span class="text-xs text-slate-400">Total: {{ $history->count() }} Record</span>
            </div>
            
            <div class="flex-1 overflow-y-auto p-0">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase text-slate-500 sticky top-0 z-10">
                        <tr>
                            <th class="p-4 font-semibold">Waktu & Tipe</th>
                            <th class="p-4 font-semibold">Referensi / Keterangan</th>
                            <th class="p-4 font-semibold text-center">User</th>
                            <th class="p-4 font-semibold text-right">Perubahan Qty</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse($history as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4">
                                <p class="text-[10px] font-bold text-slate-400 mb-1">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y, H:i') }}</p>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-{{ $item['color'] }}-100 text-{{ $item['color'] }}-700">
                                    {{ $item['type'] }}
                                </span>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-slate-800 text-xs font-mono mb-0.5">{{ $item['reference'] }}</p>
                                <p class="text-xs text-slate-500">{{ $item['description'] }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-xs font-medium bg-slate-100 px-2 py-1 rounded text-slate-600"><i class="fas fa-user-circle mr-1 text-slate-400"></i> {{ $item['user'] }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <span class="font-extrabold text-lg text-{{ $item['color'] }}-600">{{ $item['qty_change'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-slate-400">
                                <i class="fas fa-file-alt text-3xl mb-3 text-slate-300"></i>
                                <p class="font-medium text-sm">Belum ada histori pergerakan barang.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection