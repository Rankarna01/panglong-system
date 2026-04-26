@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Supplier</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola data mitra penyuplai bahan baku dan kontak person mereka.</p>
        </div>
        <button onclick="openModal('modalAdd')" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-[#4a332c] transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-handshake"></i> Tambah Supplier
        </button>
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
                
                <div class="flex gap-2">
                    <button onclick="editModal({{ $item }})" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-500 hover:text-primary hover:bg-primary/10 flex items-center justify-center transition-all" title="Edit">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <form action="{{ route('admin.supplier.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-500 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-all" title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-white border border-slate-200 flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fas fa-truck-loading text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700">Belum Ada Supplier</h3>
            <p class="text-slate-500 text-sm mt-1">Klik tombol di kanan atas untuk menambahkan mitra pertamamu.</p>
        </div>
        @endforelse
    </div>
</div>

<div id="modalAdd" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform" id="modalAddContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Tambah Supplier Baru</h3>
            <button onclick="closeModal('modalAdd')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.supplier.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Perusahaan / Supplier</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="Contoh: PT. Baja Perkasa">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nomor Telepon/WA</label>
                    <input type="text" name="phone" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="0812...">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Keterangan (Opsional)</label>
                    <input type="text" name="description" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="Contoh: Distributor Semen">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="Alamat lengkap supplier..."></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-2">
                <button type="button" onclick="closeModal('modalAdd')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl transition-all">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] transition-all shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform" id="modalEditContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Edit Data Supplier</h3>
            <button onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Perusahaan / Supplier</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nomor Telepon/WA</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Keterangan (Opsional)</label>
                    <input type="text" name="description" id="edit_desc" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Alamat Lengkap</label>
                <textarea name="address" id="edit_address" rows="3" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700"></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-2">
                <button type="button" onclick="closeModal('modalEdit')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl transition-all">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] transition-all shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // FUNGSI MODAL
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
    function editModal(item) {
        document.getElementById('editForm').action = `/admin/supplier/${item.id}`;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_phone').value = item.phone || '';
        document.getElementById('edit_desc').value = item.description || '';
        document.getElementById('edit_address').value = item.address || '';
        openModal('modalEdit');
    }

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