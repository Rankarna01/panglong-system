@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengeluaran Barang (Stok Keluar)</h1>
            <p class="text-slate-500 text-sm mt-1">Catat barang yang keluar karena rusak, kadaluarsa, atau retur.</p>
        </div>
        <button onclick="openModal('modalAdd')" class="bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-[#4a332c] transition-all flex items-center gap-2 shadow-sm">
            <i class="fas fa-minus-circle"></i> Input Barang Keluar
        </button>
    </div>

    @if($errors->any())
    <div class="bg-red-50 text-red-500 p-4 rounded-xl text-sm flex items-start gap-2 border border-red-100 shadow-sm">
        <i class="fas fa-exclamation-circle mt-0.5"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Referensi & Tanggal</th>
                        <th class="p-4 font-semibold">Nama Barang & Alasan</th>
                        <th class="p-4 font-semibold text-center">Qty Keluar</th>
                        <th class="p-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($stockOuts as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $item->reference }}</p>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="far fa-calendar-alt mr-1 text-primary/60"></i> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</p>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-primary">{{ $item->product->name ?? 'Barang Dihapus' }}</p>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-orange-50 text-orange-600 mt-1 text-[10px] font-bold uppercase tracking-wider">
                                <i class="fas fa-exclamation-circle"></i> {{ $item->reason }}
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1.5 bg-red-50 text-red-600 font-bold text-lg rounded-xl">- {{ $item->qty }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('gudang.stok-keluar.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Batal input? Riwayat ini akan dihapus dan stok akan dikembalikan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Batal & Kembalikan Stok">
                                    <i class="fas fa-undo-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-box-open text-xl"></i>
                            </div>
                            Belum ada riwayat pengeluaran barang.
                        </td>
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
            <h3 class="text-base font-bold text-primary">Input Barang Keluar</h3>
            <button onclick="closeModal('modalAdd')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <form action="{{ route('gudang.stok-keluar.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tanggal Keluar</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm text-slate-700">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Pilih Barang</label>
                    <select name="product_id" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm text-slate-700">
                        <option value="">Cari/Pilih Barang...</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->code }} - {{ $prod->name }} (Sisa: {{ $prod->stock }})</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">*Hanya menampilkan barang yang memiliki stok.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Jumlah (Qty)</label>
                        <input type="number" name="qty" min="1" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm text-slate-700" placeholder="Contoh: 5">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Alasan Keluar</label>
                        <select name="reason" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm text-slate-700">
                            <option value="">Pilih Alasan...</option>
                            <option value="Barang Rusak">Barang Rusak</option>
                            <option value="Retur Supplier">Retur Supplier</option>
                            <option value="Pemakaian Internal">Pemakaian Internal</option>
                            <option value="Kadaluarsa">Kadaluarsa</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-2">
                    <button type="button" onclick="closeModal('modalAdd')" class="px-5 py-2.5 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-[#4a332c] transition-all shadow-sm flex items-center gap-2">
                        <i class="fas fa-save"></i> Kurangi Stok
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
</script>
@endsection