@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Penjualan</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau detail setiap transaksi dan struk yang diproses oleh Kasir.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Invoice & Tanggal</th>
                        <th class="p-4 font-semibold">Kasir Bertugas</th>
                        <th class="p-4 font-semibold text-center">Item</th>
                        <th class="p-4 font-semibold text-right">Total Belanja</th>
                        <th class="p-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($sales as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <p class="font-bold text-primary">{{ $item->invoice }}</p>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="far fa-clock mr-1 text-primary/60"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium"><i class="fas fa-user-circle text-[10px] mr-1 text-primary/60"></i> {{ $item->user->name ?? '-' }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="font-bold text-slate-800">{{ $item->details->count() }}</span> <span class="text-xs text-slate-500">Macam</span>
                        </td>
                        <td class="p-4 text-right">
                            <p class="font-bold text-slate-800">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick='openDetailModal(@json($item))' class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-bold hover:bg-primary hover:text-white transition-all flex items-center gap-1.5 mx-auto">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-receipt text-xl"></i>
                            </div>
                            Belum ada riwayat transaksi penjualan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalDetail" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 overflow-hidden transform scale-95 transition-transform flex flex-col max-h-[90vh]" id="modalDetailContent">
        
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface shrink-0">
            <div>
                <h3 class="text-base font-bold text-primary">Detail Transaksi</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="modal_invoice_no">INV-XXXX</p>
            </div>
            <button onclick="closeModal('modalDetail')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Waktu Transaksi</p>
                    <p class="text-sm font-semibold text-slate-800" id="modal_time">-</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Kasir Bertugas</p>
                    <p class="text-sm font-semibold text-slate-800" id="modal_cashier">-</p>
                </div>
            </div>

            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Item Pembelian</h4>
            
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-[10px] uppercase text-slate-500">
                            <th class="py-2 px-4 font-semibold">Nama Barang</th>
                            <th class="py-2 px-4 font-semibold text-center">Harga</th>
                            <th class="py-2 px-4 font-semibold text-center">Qty</th>
                            <th class="py-2 px-4 font-semibold text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modal_item_list" class="text-sm text-slate-700 divide-y divide-slate-100">
                        </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <div class="w-1/2 p-4 bg-primary/5 rounded-xl border border-primary/10">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-600">Total Pembayaran</span>
                        <span class="text-lg font-bold text-primary" id="modal_total">Rp 0</span>
                    </div>
                </div>
            </div>
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

    // Fungsi format rupiah
    const formatRp = (angka) => {
        return new Intl.NumberFormat('id-ID').format(angka);
    };

    function openDetailModal(data) {
        // Isi info header
        document.getElementById('modal_invoice_no').innerText = data.invoice;
        
        // Format tanggal (basic JS format)
        let dateObj = new Date(data.created_at);
        document.getElementById('modal_time').innerText = dateObj.toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'}) + ', ' + dateObj.getHours() + ':' + String(dateObj.getMinutes()).padStart(2, '0');
        
        document.getElementById('modal_cashier').innerText = data.user ? data.user.name : '-';
        document.getElementById('modal_total').innerText = 'Rp ' + formatRp(data.total_amount);

        // Render tabel item
        const tbody = document.getElementById('modal_item_list');
        tbody.innerHTML = ''; // Kosongkan dulu

        if(data.details && data.details.length > 0) {
            data.details.forEach(item => {
                let productName = item.product ? item.product.name : 'Barang Dihapus';
                
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="py-3 px-4 font-medium">${productName}</td>
                    <td class="py-3 px-4 text-center text-slate-500">Rp ${formatRp(item.price)}</td>
                    <td class="py-3 px-4 text-center font-bold">x${item.qty}</td>
                    <td class="py-3 px-4 text-right font-bold text-slate-800">Rp ${formatRp(item.subtotal)}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-slate-400 text-xs">Tidak ada detail item</td></tr>`;
        }

        openModal('modalDetail');
    }
</script>
@endsection