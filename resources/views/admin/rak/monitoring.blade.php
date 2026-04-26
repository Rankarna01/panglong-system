@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-10">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Monitoring & Alokasi Rak</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau isi setiap rak dan alokasikan barang dari area transit.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm flex items-start gap-3 border border-emerald-100">
            <i class="fas fa-check-circle mt-0.5 text-lg"></i><span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm flex items-start gap-3 border border-red-100">
            <i class="fas fa-exclamation-circle mt-0.5 text-lg"></i><span class="font-medium">{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-orange-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center shadow-sm">
                <i class="fas fa-truck-loading"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-orange-800">Area Transit (Barang Belum Masuk Rak)</h3>
                <p class="text-[10px] text-orange-600">Alokasikan barang-barang di bawah ini ke dalam rak yang sesuai.</p>
            </div>
        </div>
        
        <div class="p-4 flex gap-3 overflow-x-auto pb-4 custom-scrollbar">
            @forelse($unallocatedProducts as $item)
                <div class="min-w-[200px] max-w-[220px] bg-white border border-slate-200 p-3 rounded-xl shadow-sm shrink-0 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center shrink-0 border border-slate-200 overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-box text-slate-400"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate" title="{{ $item->name }}">{{ $item->name }}</p>
                        <p class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $item->code }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-bold rounded">
                            Sisa: {{ fmod($item->unallocated_qty, 1) !== 0.00 ? rtrim(rtrim($item->unallocated_qty, '0'), '.') : number_format($item->unallocated_qty, 0) }} {{ $item->baseUnit->short_name ?? '' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-4 text-slate-400 text-sm font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500"></i> Semua barang sudah tersusun rapi di dalam rak!
                </div>
            @endforelse
        </div>
    </div>

    <h3 class="font-bold text-slate-800 text-lg mt-8 mb-4 border-b border-slate-200 pb-2">Visualisasi Gudang (Master Rak)</h3>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($racks as $rack)
        <div onclick='openRackModal(@json($rack))' class="bg-white border border-slate-200 p-5 rounded-2xl cursor-pointer hover:border-primary hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fas fa-pallet text-xl"></i>
                </div>
                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-mono font-bold">{{ $rack->code }}</span>
            </div>
            <h4 class="font-bold text-slate-800 text-lg leading-tight group-hover:text-primary transition-colors">{{ $rack->name }}</h4>
            <p class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $rack->description ?? 'Tidak ada deskripsi' }}</p>
            
            <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                <span class="text-xs font-semibold text-slate-500">Total Macam Barang</span>
                <span class="text-sm font-bold bg-secondary/10 text-secondary px-3 py-1 rounded-lg">{{ $rack->products->count() }} Item</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div id="modalRack" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-3xl mx-4 overflow-hidden transform scale-95 transition-transform shadow-2xl flex flex-col max-h-[90vh]" id="modalRackContent">
        
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-primary text-white shrink-0">
            <div>
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i class="fas fa-pallet text-white/70"></i> <span id="modalRackName">Nama Rak</span>
                </h3>
                <p class="text-xs text-white/70 mt-0.5 font-mono" id="modalRackCode">Kode Rak</p>
            </div>
            <button onclick="closeModal('modalRack')" class="text-white/60 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-0 flex flex-col md:flex-row">
            
            <div class="flex-1 p-6 border-b md:border-b-0 md:border-r border-slate-100 bg-slate-50/50">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4"><i class="fas fa-box-open text-primary mr-1"></i> Isi Rak Saat Ini</h4>
                <div class="space-y-3" id="rackContents">
                    </div>
            </div>

            <div class="w-full md:w-80 p-6 bg-white shrink-0">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4"><i class="fas fa-plus-circle text-emerald-500 mr-1"></i> Susun Barang ke Rak</h4>
                
                <form id="allocateForm" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Pilih Barang dari Area Transit</label>
                        <select name="product_id" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm text-slate-700 font-medium">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($unallocatedProducts as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} (Sisa: {{ fmod($item->unallocated_qty, 1) !== 0.00 ? rtrim(rtrim($item->unallocated_qty, '0'), '.') : number_format($item->unallocated_qty, 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Jumlah Dimasukkan</label>
                        <input type="number" step="0.01" name="qty" required class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm text-slate-800 font-bold" placeholder="0">
                        <p class="text-[10px] text-slate-400 mt-1 italic">*Input menggunakan satuan dasar barang.</p>
                    </div>
                    
                    <button type="submit" class="w-full mt-2 bg-primary text-white text-sm font-bold py-3 rounded-xl hover:bg-[#4a332c] transition-all shadow-md">
                        Simpan ke Rak
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style untuk scrollbar horizontal Area Transit */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<script>
    function openModal(id) {
        const m = document.getElementById(id), c = document.getElementById(id+'Content');
        m.classList.remove('hidden'); m.classList.add('flex');
        setTimeout(() => { m.classList.remove('opacity-0'); c.classList.remove('scale-95'); }, 10);
    }
    function closeModal(id) {
        const m = document.getElementById(id), c = document.getElementById(id+'Content');
        m.classList.add('opacity-0'); c.classList.add('scale-95');
        setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
    }

    // Fungsi Render Modal Rak
    function openRackModal(rack) {
        document.getElementById('modalRackName').innerText = rack.name;
        document.getElementById('modalRackCode').innerText = rack.code;
        
        // Set action form ke ID rak yang sedang diklik
        document.getElementById('allocateForm').action = `/admin/monitoring-rak/${rack.id}/allocate`;

        // Render isi rak (kiri)
        let contentsContainer = document.getElementById('rackContents');
        let html = '';

        if(rack.products && rack.products.length > 0) {
            rack.products.forEach(p => {
                let stockFormat = p.pivot.stock % 1 !== 0 ? p.pivot.stock : Math.floor(p.pivot.stock);
                html += `
                <div class="bg-white border border-slate-200 p-3 rounded-xl flex items-center justify-between shadow-sm">
                    <div>
                        <p class="text-sm font-bold text-slate-800">${p.name}</p>
                        <p class="text-[10px] font-mono text-slate-500">${p.code}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-1 rounded">Qty: ${stockFormat}</span>
                    </div>
                </div>`;
            });
        } else {
            html = `<div class="text-center py-6 text-slate-400 text-sm"><i class="fas fa-box-open text-3xl mb-2 text-slate-300"></i><br>Rak masih kosong.</div>`;
        }
        contentsContainer.innerHTML = html;

        openModal('modalRack');
    }
</script>
@endsection