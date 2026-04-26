@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen User</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola akun dan hak akses pegawai Panglong.</p>
        </div>
        <button onclick="openModal('modalAdd')" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-[#4a332c] transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-user-plus"></i> Tambah User
        </button>
    </div>

    @if($errors->any())
    <div class="bg-red-50 text-red-500 p-4 rounded-xl text-sm flex items-start gap-2 border border-red-100">
        <i class="fas fa-exclamation-circle mt-0.5"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Nama Lengkap & Email</th>
                        <th class="p-4 font-semibold text-center">Hak Akses (Role)</th>
                        <th class="p-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($users as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                    {{ substr($item->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $item->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            @if($item->role === 'admin')
                                <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Admin</span>
                            @elseif($item->role === 'kasir')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Kasir</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">Staff Gudang</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editModal({{ $item }})" class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                
                                @if($item->id !== Auth::id())
                                <form action="{{ route('admin.user.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                @else
                                <button disabled class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center cursor-not-allowed opacity-50" title="Tidak bisa hapus akun sendiri">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-400">Belum ada data user.</td>
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
            <h3 class="text-base font-bold text-primary">Tambah User Baru</h3>
            <button onclick="closeModal('modalAdd')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.user.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Email (Untuk Login)</label>
                <input type="email" name="email" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Password</label>
                <input type="password" name="password" required minlength="6" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm" placeholder="Minimal 6 karakter">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Hak Akses (Role)</label>
                <select name="role" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
                    <option value="">Pilih Role...</option>
                    <option value="kasir">Kasir (Transaksi POS)</option>
                    <option value="staff">Staff Gudang (Kelola Stok)</option>
                    <option value="admin">Admin (Pengawas & Laporan)</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-6">
                <button type="button" onclick="closeModal('modalAdd')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] shadow-sm">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform" id="modalEditContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Edit User</h3>
            <button onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Email</label>
                <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Hak Akses (Role)</label>
                <select name="role" id="edit_role" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm">
                    <option value="kasir">Kasir (Transaksi POS)</option>
                    <option value="staff">Staff Gudang (Kelola Stok)</option>
                    <option value="admin">Admin (Pengawas & Laporan)</option>
                </select>
            </div>
            <div class="p-4 bg-orange-50 rounded-xl border border-orange-100 mt-2">
                <label class="block text-xs font-semibold text-orange-600 mb-1.5 uppercase tracking-wider"><i class="fas fa-lock mr-1"></i> Reset Password Baru (Opsional)</label>
                <input type="password" name="password" minlength="6" class="w-full px-4 py-2.5 bg-white border border-orange-200 rounded-xl focus:ring-2 focus:ring-orange-200 outline-none transition-all text-sm" placeholder="Kosongkan jika tidak ingin diubah">
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
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
        document.getElementById('editForm').action = `/admin/user/${item.id}`;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_email').value = item.email;
        document.getElementById('edit_role').value = item.role;
        openModal('modalEdit');
    }
</script>
@endsection