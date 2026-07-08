@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Diskon</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola daftar diskon yang dapat digunakan oleh kasir pada saat transaksi POS.</p>
        </div>
        <button onclick="openModal('modalAdd')" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-[#4a332c] transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i> Tambah Diskon
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                    <th class="p-4 font-semibold w-16 text-center">No</th>
                    <th class="p-4 font-semibold">Nama Diskon</th>
                    <th class="p-4 font-semibold text-center">Tipe</th>
                    <th class="p-4 font-semibold text-right">Nilai Potongan</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">
                @forelse($discounts as $index => $item)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                    <td class="p-4 font-bold text-slate-800">{{ $item->name }}</td>
                    <td class="p-4 text-center">
                        @if($item->type === 'percentage')
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-wider">Persentase</span>
                        @else
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">Nominal Rupiah</span>
                        @endif
                    </td>
                    <td class="p-4 text-right font-bold text-slate-800">
                        @if($item->type === 'percentage')
                            {{ $item->value }}%
                        @else
                            Rp {{ number_format($item->value, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($item->is_active)
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wider"><i class="fas fa-times-circle mr-1"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="editModal({{ $item }})" class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <form action="{{ route('admin.diskon.destroy', $item->id_discount) }}" method="POST" class="inline" onsubmit="return confirm('Hapus diskon ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-10 text-center text-slate-400">Belum ada data diskon.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalAdd" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform" id="modalAddContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Tambah Diskon</h3>
            <button onclick="closeModal('modalAdd')" class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.diskon.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Diskon</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm" placeholder="Contoh: Promo Lebaran">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tipe Potongan</label>
                    <select name="type" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
                        <option value="percentage">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nilai Potongan</label>
                    <input type="number" step="0.01" min="0" name="value" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm" placeholder="Contoh: 10 atau 50000">
                </div>
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                    <span class="text-sm font-semibold text-slate-700">Aktifkan diskon ini</span>
                </label>
            </div>
            <div class="pt-2 flex justify-end gap-3 border-t border-slate-100 mt-5 pt-4">
                <button type="button" onclick="closeModal('modalAdd')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform" id="modalEditContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Edit Diskon</h3>
            <button onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Diskon</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tipe Potongan</label>
                    <select name="type" id="edit_type" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
                        <option value="percentage">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nilai Potongan</label>
                    <input type="number" step="0.01" min="0" name="value" id="edit_value" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
                </div>
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                    <span class="text-sm font-semibold text-slate-700">Aktifkan diskon ini</span>
                </label>
            </div>
            <div class="pt-2 flex justify-end gap-3 border-t border-slate-100 mt-5 pt-4">
                <button type="button" onclick="closeModal('modalEdit')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] shadow-sm">Update</button>
            </div>
        </form>
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
    function editModal(item) {
        document.getElementById('editForm').action = `/admin/diskon/${item.id_discount}`;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_type').value = item.type;
        // Konversi nilai value kalau-kalau ada angka .00
        let val = parseFloat(item.value);
        document.getElementById('edit_value').value = val;
        document.getElementById('edit_is_active').checked = item.is_active ? true : false;
        
        openModal('modalEdit');
    }
</script>
@endsection
