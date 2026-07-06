@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Supplier</h1>
            <p class="text-slate-500 text-sm mt-1">Lihat data mitra penyuplai bahan baku (Read-Only).</p>
        </div>
    </div>

    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex items-center">
        <div class="pl-4 pr-3 text-slate-400">
            <i class="fas fa-search"></i>
        </div>
        <input type="text" id="searchInput" onkeyup="searchSupplier()" placeholder="Cari nama supplier atau alamat..." class="w-full py-2.5 bg-transparent outline-none text-sm text-slate-700 font-medium placeholder:font-normal">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="supplierGrid">
        @forelse($suppliers as $item)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 supplier-card flex flex-col justify-between h-full">
            
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-bold text-xl uppercase shadow-inner">
                    {{ substr($item->name, 0, 2) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg supplier-name line-clamp-1" title="{{ $item->name }}">{{ $item->name }}</h3>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-0.5 flex items-center gap-1">
                        <i class="fas fa-user-tie"></i> Mitra Gudang
                    </p>
                </div>
            </div>

            <div class="space-y-3 mb-6 flex-1">
                <div class="flex items-start gap-3 text-sm text-slate-600">
                    <i class="fas fa-phone-alt mt-1 text-slate-400 w-4 text-center"></i>
                    <span class="font-medium">{{ $item->phone ?? 'Tidak ada kontak' }}</span>
                </div>
                <div class="flex items-start gap-3 text-sm text-slate-600">
                    <i class="fas fa-map-marker-alt mt-1 text-slate-400 w-4 text-center"></i>
                    <span class="supplier-address line-clamp-2" title="{{ $item->address }}">{{ $item->address ?? 'Alamat belum diisi' }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <span class="px-3 py-1.5 bg-primary/5 text-primary rounded-lg text-xs font-bold tracking-wide supplier-desc">
                    {{ $item->description ?? 'General Supplier' }}
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-white border border-slate-200 flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fas fa-truck-loading text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700">Belum Ada Supplier</h3>
            <p class="text-slate-500 text-sm mt-1">Data supplier akan ditambahkan oleh Admin.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    // FUNGSI PENCARIAN REAL-TIME
    function searchSupplier() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let cards = document.getElementsByClassName('supplier-card');

        for (let i = 0; i < cards.length; i++) {
            let name = cards[i].querySelector('.supplier-name').innerText.toLowerCase();
            let address = cards[i].querySelector('.supplier-address').innerText.toLowerCase();
            let desc = cards[i].querySelector('.supplier-desc').innerText.toLowerCase();

            if (name.includes(input) || address.includes(input) || desc.includes(input)) {
                cards[i].style.display = "flex";
            } else {
                cards[i].style.display = "none";
            }
        }
    }
</script>
@endsection
