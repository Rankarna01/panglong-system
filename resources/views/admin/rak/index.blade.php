@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Data Rak</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola lokasi rak penyimpanan di gudang Panglong.</p>
        </div>
        <button onclick="openModal('modalAdd')" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-[#4a332c] transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i> Tambah Rak
        </button>
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

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold w-40">Kode Rak</th>
                        <th class="p-4 font-semibold">Nama Area / Rak</th>
                        <th class="p-4 font-semibold">Keterangan</th>
                        <th class="p-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($racks as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-primary/10 text-primary font-bold rounded-lg font-mono text-xs">{{ $item->code }}</span>
                        </td>
                        <td class="p-4 font-bold text-slate-800">{{ $item->name }}</td>
                        <td class="p-4 text-slate-500">{{ $item->description ?? '-' }}</td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="editModal({{ $item }})" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('admin.rak.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus rak ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
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
                                <i class="fas fa-pallet text-xl"></i>
                            </div>
                            Belum ada data rak.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalAdd" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform" id="modalAddContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Tambah Rak Baru</h3>
            <button onclick="closeModal('modalAdd')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.rak.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Kode Rak</label>
                <input type="text" name="code" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-mono uppercase" placeholder="Contoh: RAK-A1">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Area / Rak</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="Contoh: Area Semen Depan">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Keterangan (Opsional)</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="Detail posisi..."></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalAdd')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] shadow-sm">Simpan Rak</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform" id="modalEditContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Edit Data Rak</h3>
            <button onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Kode Rak</label>
                <input type="text" name="code" id="edit_code" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-mono uppercase">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Area / Rak</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Keterangan (Opsional)</label>
                <textarea name="description" id="edit_description" rows="2" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700"></textarea>
            </div>
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalEdit')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] shadow-sm">Simpan Perubahan</button>
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
        document.getElementById('editForm').action = `/admin/rak/${item.id}`;
        document.getElementById('edit_code').value = item.code;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_description').value = item.description || '';
        openModal('modalEdit');
    }
</script>
@endsection