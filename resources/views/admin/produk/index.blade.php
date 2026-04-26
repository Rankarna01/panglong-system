@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Data Barang</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola stok utama (Base Unit), harga, dan aturan konversi satuan grosir.</p>
        </div>
        <button onclick="openModal('modalAdd')" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-[#4a332c] transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i> Tambah Barang
        </button>
    </div>

    <div class="bg-orange-50 border border-orange-200 text-orange-700 p-4 rounded-xl flex items-start gap-3 text-sm">
        <i class="fas fa-info-circle mt-0.5 text-orange-500"></i>
        <p><b>Standar ERP:</b> Pastikan <b>"Satuan"</b> yang dipilih saat menambah barang adalah <b>Satuan Terkecil (Eceran)</b>. Anda bisa menambahkan satuan grosir (Truk, Dus, Sak) melalui tombol <b><i class="fas fa-exchange-alt"></i> Konversi</b> pada tabel.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Barang & Kategori</th>
                        <th class="p-4 font-semibold text-center">Stok (Satuan Dasar)</th>
                        <th class="p-4 font-semibold text-right">Harga Dasar (Rp)</th>
                        <th class="p-4 font-semibold text-center w-40">Aksi & Konversi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($products as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-sm" alt="Foto">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200 shadow-sm">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-slate-800 text-base">{{ $item->name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-mono">{{ $item->code }}</span>
                                        <span class="text-[10px] text-slate-500 font-bold uppercase">{{ $item->category->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="p-4 text-center">
                            <span class="font-bold text-xl {{ $item->stock <= $item->min_stock ? 'text-red-500' : 'text-slate-800' }}">
                                {{ fmod($item->stock, 1) !== 0.00 ? rtrim(rtrim($item->stock, '0'), '.') : number_format($item->stock, 0) }}
                            </span>
                            <span class="text-xs font-bold text-primary ml-1 bg-primary/10 px-2 py-0.5 rounded-lg">{{ $item->unit->short_name ?? $item->unit->name ?? '' }}</span>
                            
                            @if($item->conversions->count() > 0)
                                <p class="text-[10px] text-emerald-600 font-bold mt-1"><i class="fas fa-check-circle"></i> {{ $item->conversions->count() }} Konversi Aktif</p>
                            @else
                                <p class="text-[10px] text-orange-500 font-bold mt-1"><i class="fas fa-exclamation-triangle"></i> Belum ada konversi</p>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <p class="font-bold text-slate-800 text-lg">{{ number_format($item->price, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-slate-400">per {{ $item->unit->name ?? 'satuan' }}</p>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick='openConvModal(@json($item))' class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-bold hover:bg-emerald-500 hover:text-white transition-all flex items-center gap-1.5" title="Atur Konversi Satuan">
                                    <i class="fas fa-exchange-alt"></i> Konversi
                                </button>
                                
                                <button onclick="editModal({{ $item }})" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-primary hover:text-white transition-all" title="Edit Master">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('admin.barang.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang ini beserta seluruh aturan konversinya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-box-open text-xl"></i>
                            </div>
                            Belum ada data barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalAdd" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform" id="modalAddContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Tambah Barang Baru</h3>
            <button onclick="closeModal('modalAdd')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        
        <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Foto Barang (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Barang</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="Contoh: Semen Padang">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
                        <option value="">Pilih...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-primary mb-1.5 uppercase tracking-wider">Satuan Dasar (Terkecil)</label>
                    <select name="unit_id" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700 bg-primary/5">
                        <option value="">Pilih (Eceran)...</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Stok Awal</label>
                    <input type="number" step="0.01" name="stock" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" value="0">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Batas Minimum</label>
                    <input type="number" step="0.01" name="min_stock" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" value="5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-primary mb-1.5 uppercase tracking-wider">Harga Jual (Sesuai Satuan Dasar)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">Rp</span>
                    <input type="number" name="price" required class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold text-slate-800" placeholder="0">
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalAdd')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl transition-all">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] transition-all shadow-sm">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform" id="modalEditContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Edit Master Barang</h3>
            <button onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Ubah Foto Barang (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                <p class="text-[10px] text-slate-400 mt-1 italic">*Biarkan kosong jika tidak ingin mengganti foto saat ini.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Barang</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Kategori</label>
                    <select name="category_id" id="edit_category" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
                        @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-primary mb-1.5 uppercase tracking-wider">Satuan Dasar</label>
                    <select name="unit_id" id="edit_unit" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700 bg-primary/5">
                        @foreach($units as $unit) <option value="{{ $unit->id }}">{{ $unit->name }}</option> @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Stok Saat Ini</label>
                    <input type="number" step="0.01" name="stock" id="edit_stock" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Batas Minimum</label>
                    <input type="number" step="0.01" name="min_stock" id="edit_min_stock" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-primary mb-1.5 uppercase tracking-wider">Harga Dasar (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">Rp</span>
                    <input type="number" name="price" id="edit_price" required class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold text-slate-800">
                </div>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalEdit')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl transition-all">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] transition-all shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalConv" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-xl mx-4 overflow-hidden transform scale-95 transition-transform" id="modalConvContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-emerald-600 text-white">
            <div>
                <h3 class="text-base font-bold">Atur Konversi Satuan</h3>
                <p class="text-xs text-white/80 mt-0.5" id="convProductName">Nama Barang</p>
            </div>
            <button onclick="closeModal('modalConv')" class="text-white/60 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="p-6 flex flex-col gap-6">
            <form id="convForm" method="POST" class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl shadow-sm">
                @csrf
                <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-3"><i class="fas fa-plus-circle"></i> Buat Rumus Konversi Baru</p>
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-[10px] font-semibold text-emerald-700 mb-1">Satuan Grosir (Besar)</label>
                        <select name="unit_id" required class="w-full px-3 py-2 bg-white border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm text-slate-700">
                            <option value="">Pilih...</option>
                            @foreach($units as $unit) <option value="{{ $unit->id }}">{{ $unit->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="pb-2 font-bold text-slate-400">=</div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-semibold text-emerald-700 mb-1" id="convBaseLabel">Nilai Pengali</label>
                        <input type="number" step="0.01" name="multiplier" required class="w-full px-3 py-2 bg-white border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm text-slate-700 font-bold" placeholder="Contoh: 30">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition-all shadow-sm">Simpan</button>
                </div>
            </form>

            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konversi Aktif Saat Ini</p>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase text-slate-500">
                            <tr>
                                <th class="p-3 font-semibold">1 Satuan Grosir</th>
                                <th class="p-3 font-semibold">Sama Dengan (Satuan Dasar)</th>
                                <th class="p-3 font-semibold text-center w-16">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="convList" class="text-sm text-slate-700 divide-y divide-slate-100">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // GLOBAL MODAL LOGIC
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(modalId + 'Content');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.classList.remove('opacity-0'); content.classList.remove('scale-95'); }, 10);
    }
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(modalId + 'Content');
        modal.classList.add('opacity-0'); content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
    }

    function editModal(item) {
        document.getElementById('editForm').action = `/admin/barang/${item.id}`;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_category').value = item.category_id;
        document.getElementById('edit_unit').value = item.unit_id;
        document.getElementById('edit_stock').value = item.stock;
        document.getElementById('edit_min_stock').value = item.min_stock;
        document.getElementById('edit_price').value = Math.floor(item.price);
        openModal('modalEdit');
    }

    // KONVERSI MODAL LOGIC
    function openConvModal(product) {
        document.getElementById('convForm').action = `/admin/barang/${product.id}/konversi`;
        document.getElementById('convProductName').innerText = product.code + ' - ' + product.name;
        
        let baseUnitName = product.unit ? product.unit.name : 'Satuan Dasar';
        document.getElementById('convBaseLabel').innerText = `Dikali (dalam ${baseUnitName})`;

        let tbody = document.getElementById('convList');
        let html = '';
        
        if(product.conversions && product.conversions.length > 0) {
            product.conversions.forEach(c => {
                let unitName = c.unit ? c.unit.name : 'Unknown';
                html += `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-3 font-bold text-emerald-600">1 ${unitName}</td>
                    <td class="p-3 font-bold text-slate-800">= ${c.multiplier} <span class="font-normal text-slate-500">${baseUnitName}</span></td>
                    <td class="p-3 text-center">
                        <form action="/admin/konversi/${c.id}" method="POST" class="inline" onsubmit="return confirm('Hapus konversi ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="w-7 h-7 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded flex items-center justify-center transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                        </form>
                    </td>
                </tr>`;
            });
        } else {
            html = `<tr><td colspan="3" class="p-6 text-center text-slate-400"><i class="fas fa-exclamation-triangle text-xl mb-2 block"></i> Belum ada aturan konversi.</td></tr>`;
        }
        tbody.innerHTML = html;
        openModal('modalConv');
    }
</script>
@endsection