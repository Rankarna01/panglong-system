@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Cek Data Barang & Kartu Stok</h1>
            <p class="text-slate-500 text-sm mt-1">Pilih barang untuk melihat lokasi rak dan riwayat pergerakan stoknya.</p>
        </div>
    </div>

    <form action="{{ route('gudang.cek-barang.index') }}" method="GET" class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang atau kode (Contoh: Semen)..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
        </div>
        <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-[#4a332c] transition-all shadow-sm">Cari</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($products as $item)
        <a href="{{ route('gudang.cek-barang.show', $item->id) }}" class="bg-white border border-slate-200 p-4 rounded-2xl cursor-pointer hover:border-primary hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group block">
            <div class="w-full h-36 bg-slate-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden border border-slate-100">
                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <i class="fas fa-image text-3xl text-slate-300"></i>
                @endif
            </div>
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item->code }}</span>
                <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-md font-bold border border-primary/20">Stok: {{ fmod($item->stock, 1) !== 0.00 ? rtrim(rtrim($item->stock, '0'), '.') : number_format($item->stock, 0) }}</span>
            </div>
            <h3 class="font-bold text-slate-800 text-sm line-clamp-2 group-hover:text-primary transition-colors">{{ $item->name }}</h3>
        </a>
        @empty
        <div class="col-span-full py-12 text-center bg-white border border-slate-200 rounded-2xl">
            <i class="fas fa-box-open text-4xl text-slate-300 mb-3"></i>
            <p class="font-bold text-slate-600">Barang tidak ditemukan</p>
            <p class="text-sm text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection