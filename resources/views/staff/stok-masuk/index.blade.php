@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Penerimaan Barang</h1>
            <p class="text-slate-500 text-sm mt-1">Catat fisik barang yang masuk dari Supplier beserta satuannya.</p>
        </div>
        <button onclick="openModal('modalAdd')" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-[#4a332c] transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i> Input Barang Masuk
        </button>
    </div>

    @if($errors->any())
    <div class="bg-red-50 text-red-500 p-4 rounded-xl text-sm flex items-start gap-2 border border-red-100">
        <i class="fas fa-exclamation-circle mt-0.5"></i><span>{{ $errors->first() }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Referensi & Tanggal</th>
                        <th class="p-4 font-semibold">Barang & Supplier</th>
                        <th class="p-4 font-semibold text-center">Pembayaran</th>
                        <th class="p-4 font-semibold text-center">Penambahan Stok (Dasar)</th>
                        <th class="p-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($stockIns as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $item->reference }}</p>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="far fa-calendar-alt mr-1 text-primary/60"></i> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</p>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-primary">{{ $item->product->name ?? 'Barang Dihapus' }}</p>
                            <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-wider"><i class="fas fa-truck text-[10px] mr-1"></i> {{ $item->supplier->name ?? '-' }}</p>
                            @if($item->notes)
                            <p class="text-[10px] text-slate-400 mt-0.5 italic"><i class="fas fa-info-circle"></i> {{ $item->notes }}</p>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($item->payment_method == 'transfer')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Transfer</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Cash</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 font-bold text-lg rounded-xl">+ {{ $item->qty }} <span class="text-xs font-normal">{{ $item->product->baseUnit->short_name ?? '' }}</span></span>
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('gudang.stok-masuk.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Batal input? Stok barang akan dikurangi kembali.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Batal & Kurangi Stok">
                                    <i class="fas fa-undo-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-slate-400">Belum ada penerimaan barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalAdd" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform flex flex-col max-h-[90vh]" id="modalAddContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface shrink-0">
            <h3 class="text-base font-bold text-primary">Input Penerimaan Barang</h3>
            <button onclick="closeModal('modalAdd')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('gudang.stok-masuk.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Tanggal Masuk</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:border-primary outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Supplier</label>
                        <select name="supplier_id" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:border-primary outline-none text-sm">
                            <option value="">Pilih Supplier...</option>
                            @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-primary mb-1.5 uppercase">Pilih Barang</label>
                    <select name="product_id" id="productSelect" onchange="updateUnitOptions()" required class="w-full px-4 py-2.5 bg-primary/5 border border-primary/20 rounded-xl focus:ring-2 focus:border-primary outline-none text-sm font-bold text-slate-700">
                        <option value="">Cari/Pilih Barang...</option>
                        @foreach($products as $prod)
                            @php
                                // Susun JSON array berisi Satuan Dasar + Satuan Grosirnya
                                $validUnits = [
                                    ['id' => $prod->unit_id, 'name' => ($prod->baseUnit->name ?? 'Base') . ' (Satuan Dasar)']
                                ];
                                foreach($prod->conversions as $conv) {
                                    $validUnits[] = ['id' => $conv->unit_id, 'name' => ($conv->unit->name ?? 'Grosir') . ' (Isi '.$conv->multiplier.')'];
                                }
                            @endphp
                            <option value="{{ $prod->id }}" data-units="{{ json_encode($validUnits) }}">{{ $prod->code }} - {{ $prod->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Jumlah Masuk</label>
                        <input type="number" step="0.01" name="input_qty" min="0.1" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:border-primary outline-none text-lg font-bold text-slate-800" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-emerald-600 mb-1.5 uppercase">Satuan Diterima</label>
                        <select name="unit_id" id="unitSelect" required disabled class="w-full px-4 py-2.5 bg-white border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none text-sm text-slate-700 disabled:opacity-50 disabled:bg-slate-100">
                            <option value="">Pilih Barang Dulu...</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Pembayaran</label>
                        <select name="payment_method" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl outline-none text-sm">
                            <option value="cash">Tunai (Cash)</option><option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Catatan Surat Jalan</label>
                        <input type="text" name="notes" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl outline-none text-sm" placeholder="Opsional...">
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-2">
                    <button type="button" onclick="closeModal('modalAdd')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan & Hitung Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    // MAGIC JAVASCRIPT: Ubah Satuan Otomatis
    function updateUnitOptions() {
        let prodSelect = document.getElementById('productSelect');
        let unitSelect = document.getElementById('unitSelect');
        
        let selectedOption = prodSelect.options[prodSelect.selectedIndex];
        
        if(!selectedOption.value) {
            unitSelect.innerHTML = '<option value="">Pilih Barang Dulu...</option>';
            unitSelect.disabled = true;
            return;
        }

        // Ambil data satuan dari atribut HTML
        let units = JSON.parse(selectedOption.getAttribute('data-units'));
        
        let html = '';
        units.forEach(u => {
            html += `<option value="${u.id}">${u.name}</option>`;
        });
        
        unitSelect.innerHTML = html;
        unitSelect.disabled = false; // Aktifkan dropdown satuan
    }
</script>
@endsection