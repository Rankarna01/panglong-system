@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-10">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Utama</h1>
            <p class="text-slate-500 text-sm mt-1">Ringkasan performa bisnis dan operasional FlowInti Panglong.</p>
        </div>
        <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-200 flex items-center gap-3 shadow-sm">
            <i class="fas fa-calendar-day text-primary"></i>
            <span class="font-bold text-sm text-slate-700">{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300 border-b-4 border-b-transparent hover:border-b-primary">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6">
                <i class="fas fa-shopping-cart text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Penjualan Hari Ini</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $data['total_sales_today'] }}</h3>
                    <span class="text-xs font-bold text-slate-400 italic">Trx</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300 border-b-4 border-b-transparent hover:border-b-secondary">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center mb-6">
                <i class="fas fa-boxes text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Varian Produk</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $data['total_products'] }}</h3>
                    <span class="text-xs font-bold text-slate-400 italic">Item</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden border-b-4 border-b-transparent hover:border-b-red-500">
            @if($data['low_stock_count'] > 0)
                <div class="absolute top-0 right-0 w-16 h-16 bg-red-500 rounded-bl-full opacity-10"></div>
            @endif
            
            <div class="w-12 h-12 rounded-xl {{ $data['low_stock_count'] > 0 ? 'bg-red-50 text-red-500' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center mb-6">
                <i class="fas fa-exclamation-triangle text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bahan Baku Menipis</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-extrabold {{ $data['low_stock_count'] > 0 ? 'text-red-500' : 'text-slate-800' }}">{{ $data['low_stock_count'] }}</h3>
                    <span class="text-xs font-bold text-slate-400 italic">Barang</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col justify-between hover:-translate-y-1 transition-transform duration-300 border-b-4 border-b-transparent hover:border-b-slate-400">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center mb-6">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Akun Sistem</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $data['total_users'] }}</h3>
                    <span class="text-xs font-bold text-slate-400 italic">User</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tren Penjualan (7 Hari)</h3>
            </div>
            <div class="h-[280px] w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Kesehatan Stok</h3>
            </div>
            <div class="flex-1 flex items-center justify-center relative">
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-[-20px]">
                    <span class="text-3xl font-extrabold text-slate-800">{{ $data['total_products'] }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Item</span>
                </div>
                <div class="h-[220px] w-full">
                    <canvas id="stockHealthChart"></canvas>
                </div>
            </div>
            <div class="flex items-center justify-center gap-6 mt-4">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#A1887F]"></span><span class="text-xs font-medium text-slate-600">Aman</span></div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-[#ef4444]"></span><span class="text-xs font-medium text-slate-600">Kritis</span></div>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-surface/50">
            <div class="flex items-center gap-2">
                <i class="fas fa-history text-primary"></i>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Aktivitas Toko Terbaru</h3>
            </div>
            <a href="#" class="text-xs font-bold text-primary hover:text-[#4a332c] bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">Lihat Laporan</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($recent_activities as $activity)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="p-4 w-16 text-center">
                            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors mx-auto">
                                <i class="fas fa-receipt text-sm"></i>
                            </div>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $activity->invoice }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Penjualan Kasir</p>
                        </td>
                        <td class="p-4">
                            <p class="text-xs text-slate-500">Dilayani oleh</p>
                            <p class="font-bold text-slate-700 text-xs mt-0.5"><i class="fas fa-user-circle text-slate-400 mr-1"></i> {{ $activity->user->name }}</p>
                        </td>
                        <td class="p-4 text-right">
                            <p class="font-bold text-primary text-base">Rp {{ number_format($activity->total_amount, 0, ',', '.') }}</p>
                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5 uppercase tracking-wider"><i class="far fa-clock mr-1"></i> {{ $activity->created_at->diffForHumans() }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mx-auto mb-4"><i class="fas fa-inbox text-2xl"></i></div>
                            <p class="font-bold text-slate-600">Belum Ada Transaksi</p>
                            <p class="text-xs text-slate-400 mt-1">Data penjualan terbaru akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. TREN TRANSAKSI BAR CHART (TEMA COKELAT)
        const ctxBar = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart_data->pluck('date')) !!},
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($chart_data->pluck('total')) !!},
                    backgroundColor: '#5D4037', // Warna Primary Cokelat
                    borderRadius: 6,
                    barThickness: 28,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b', padding: 12, cornerRadius: 8,
                        titleFont: { family: 'Poppins', size: 12 },
                        bodyFont: { family: 'Poppins', size: 14, weight: 'bold' }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, border: { display: false },
                        grid: { color: '#f1f5f9', drawTicks: false },
                        ticks: { color: '#94a3b8', font: { family: 'Poppins', size: 11 }, padding: 10 }
                    },
                    x: { 
                        border: { display: false }, grid: { display: false },
                        ticks: { color: '#94a3b8', font: { family: 'Poppins', size: 11 }, padding: 10 }
                    }
                }
            }
        });

        // 2. KESEHATAN STOK DOUGHNUT CHART (TEMA COKELAT & MERAH)
        const totalItems = {{ $data['total_products'] }};
        const criticalItems = {{ $data['low_stock_count'] }};
        const safeItems = totalItems - criticalItems;

        const ctxPie = document.getElementById('stockHealthChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Aman', 'Kritis'],
                datasets: [{
                    data: [safeItems, criticalItems],
                    backgroundColor: ['#A1887F', '#ef4444'], // Cokelat Muda (Aman) & Merah (Kritis)
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', 
                plugins: {
                    legend: { display: false }, 
                    tooltip: {
                        backgroundColor: '#1e293b', padding: 12, cornerRadius: 8,
                        titleFont: { family: 'Poppins', size: 12 },
                        bodyFont: { family: 'Poppins', size: 14, weight: 'bold' }
                    }
                },
                layout: {
                    padding: { top: 10, bottom: 20 }
                }
            }
        });
    });
</script>
@endsection