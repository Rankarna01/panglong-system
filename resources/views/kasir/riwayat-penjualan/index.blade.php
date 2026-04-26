@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Penjualan Saya</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau performa dan histori transaksi yang telah Anda proses.</p>
        </div>
        <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-200 flex items-center gap-3">
            <i class="fas fa-calendar-alt text-primary"></i>
            <span class="font-medium text-sm text-slate-700">{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pendapatan Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($revenue_today, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Transaksi Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $sales_today }} <span class="text-sm font-medium text-slate-400">Struk</span></h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-800">Tren Pendapatan (7 Hari Terakhir)</h3>
            </div>
            <div class="h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-primary p-6 rounded-2xl shadow-md text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mb-4 backdrop-blur-sm text-xl">
                    <i class="fas fa-award"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">Kerja Bagus, {{ explode(' ', Auth::user()->name)[0] }}!</h3>
                <p class="text-sm text-white/80 leading-relaxed">Terus tingkatkan pelayanan untuk mencapai target penjualan hari ini. Pastikan uang yang diterima di laci kasir sesuai dengan total pendapatan sistem.</p>
            </div>
            <a href="{{ route('kasir.pos.index') }}" class="mt-6 w-full py-3 bg-white text-primary text-center font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                Kembali ke POS
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Daftar Transaksi Anda</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">No. Invoice</th>
                        <th class="p-4 font-semibold">Waktu Transaksi</th>
                        <th class="p-4 font-semibold text-center">Jumlah Item</th>
                        <th class="p-4 font-semibold text-right">Total Belanja</th>
                        <th class="p-4 font-semibold text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($sales as $index => $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-4 font-bold text-primary">{{ $item->invoice }}</td>
                        <td class="p-4 text-slate-600">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">{{ $item->details->count() }} Macam</span>
                        </td>
                        <td class="p-4 text-right font-bold text-slate-800">
                            Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick='openDetailModal(@json($item))' class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-bold hover:bg-primary hover:text-white transition-all flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                
                                <a href="{{ route('kasir.pos.print', $item->id) }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition-all shadow-sm" title="Cetak Struk">
                                    <i class="fas fa-print text-xs"></i>
                                </a>
                            </div>
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
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform flex flex-col max-h-[90vh]" id="modalDetailContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface shrink-0">
            <div>
                <h3 class="text-base font-bold text-primary">Detail Struk Belanja</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="modal_invoice_no">INV-XXXX</p>
            </div>
            <button onclick="closeModal('modalDetail')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-[10px] uppercase text-slate-500">
                        <th class="py-2 px-3 font-semibold">Barang</th>
                        <th class="py-2 px-3 font-semibold text-center">Harga</th>
                        <th class="py-2 px-3 font-semibold text-center">Qty</th>
                        <th class="py-2 px-3 font-semibold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="modal_item_list" class="text-sm text-slate-700 divide-y divide-slate-100">
                    </tbody>
            </table>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="#" id="modal_btn_print" target="_blank" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors text-sm flex items-center gap-2">
                    <i class="fas fa-print"></i> Cetak Ulang
                </a>
                
                <div class="p-3 bg-primary/5 rounded-xl border border-primary/10 text-right w-1/2">
                    <span class="text-xs font-bold text-slate-500 block mb-1">Total Dibayar</span>
                    <span class="text-xl font-bold text-primary" id="modal_total">Rp 0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // FUNGSI MODAL & RENDER DETAIL
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

    const formatRp = (angka) => new Intl.NumberFormat('id-ID').format(angka);

    function openDetailModal(data) {
        document.getElementById('modal_invoice_no').innerText = data.invoice;
        document.getElementById('modal_total').innerText = 'Rp ' + formatRp(data.total_amount);
        
        // Dinamis mengganti link print di tombol modal sesuai ID struk
        document.getElementById('modal_btn_print').href = `/kasir/pos/print/${data.id}`;

        const tbody = document.getElementById('modal_item_list');
        tbody.innerHTML = ''; 

        if(data.details && data.details.length > 0) {
            data.details.forEach(item => {
                let productName = item.product ? item.product.name : 'Barang Terhapus';
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="py-3 px-3 font-medium">${productName}</td>
                    <td class="py-3 px-3 text-center text-slate-500">Rp ${formatRp(item.price)}</td>
                    <td class="py-3 px-3 text-center font-bold">x${item.qty}</td>
                    <td class="py-3 px-3 text-right font-bold text-slate-800">Rp ${formatRp(item.subtotal)}</td>
                `;
                tbody.appendChild(tr);
            });
        }
        openModal('modalDetail');
    }

    // CHART JS CONFIGURATION
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart_dates) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chart_totals) !!},
                borderColor: '#5D4037',
                backgroundColor: 'rgba(93, 64, 55, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#5D4037',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    backgroundColor: '#1e293b', padding: 12, 
                    titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Poppins', size: 14, weight: 'bold' },
                    callbacks: {
                        label: function(context) { return ' Rp ' + formatRp(context.raw); }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Poppins' } } },
                x: { border: { display: false }, grid: { display: false }, ticks: { font: { family: 'Poppins' } } }
            }
        }
    });
</script>
@endsection