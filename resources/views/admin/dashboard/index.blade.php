@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Utama</h1>
            <p class="text-slate-500 text-sm mt-1">Ringkasan performa bisnis dan operasional Panglong Anda.</p>
        </div>
        <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-200 flex items-center gap-3">
            <i class="fas fa-calendar-alt text-primary"></i>
            <span class="font-medium text-sm text-slate-700">{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-primary transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-primary transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4">
                <i class="fas fa-shopping-cart text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Penjualan Hari Ini</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">{{ $data['total_sales_today'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Trx</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-red-500 transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-red-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Bahan Baku Menipis</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">{{ $data['low_stock_count'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Item</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-emerald-500 transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-emerald-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4">
                <i class="fas fa-boxes text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Total Varian Produk</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">{{ $data['total_products'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Macam</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-purple-500 transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center mb-4">
                <i class="fas fa-users text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Total Akun User</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">{{ $data['total_users'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Akun</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-800">Tren Penjualan (7 Hari Terakhir)</h3>
            </div>
            <div class="h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-800">Aktivitas Toko</h3>
                <a href="#" class="text-xs font-semibold text-primary hover:underline">Lihat Laporan</a>
            </div>
            
            <div class="space-y-0"> @forelse($recent_activities as $activity)
                <div class="flex items-center gap-4 py-3 border-b border-slate-100 last:border-0">
                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <i class="fas fa-receipt text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800">{{ $activity->invoice }}</p>
                        <p class="text-xs text-slate-500">Oleh: <span class="font-medium text-slate-700">{{ $activity->user->name }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-800">Rp {{ number_format($activity->total_amount, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-wider">{{ $activity->created_at->format('d M') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Belum ada aktivitas.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart_data->pluck('date')) !!},
            datasets: [{
                label: 'Jumlah Transaksi',
                data: {!! json_encode($chart_data->pluck('total')) !!},
                backgroundColor: '#5D4037', // Primary Coklat
                borderRadius: 4, // Sedikit membulat di atas bar
                barThickness: 24, // Dibuat lebih ramping agar elegan
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { family: 'Poppins', size: 12 },
                    bodyFont: { family: 'Poppins', size: 13, weight: 'bold' },
                    cornerRadius: 8,
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    border: { display: false },
                    grid: { color: '#f1f5f9', drawTicks: false },
                    ticks: { color: '#94a3b8', font: { family: 'Poppins', size: 11 }, padding: 10 }
                },
                x: { 
                    border: { display: false },
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { family: 'Poppins', size: 11 }, padding: 10 }
                }
            }
        }
    });
</script>
@endsection