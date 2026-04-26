@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Gudang</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau lalu lintas dan ketersediaan fisik barang Panglong.</p>
        </div>
        <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-200 flex items-center gap-3">
            <i class="fas fa-calendar-alt text-primary"></i>
            <span class="font-medium text-sm text-slate-700">{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-emerald-500 transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-emerald-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4">
                <i class="fas fa-box-open text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Barang Masuk Hari Ini</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">+{{ $data['stock_in_today'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Qty</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-orange-500 transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-orange-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mb-4">
                <i class="fas fa-dolly text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Barang Keluar Hari Ini</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">-{{ $data['stock_out_today'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Qty</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-red-500 transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-red-500 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Perlu Restock</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">{{ $data['low_stock'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Macam Barang</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 relative overflow-hidden group hover:border-primary transition-colors duration-300">
            <div class="absolute bottom-0 left-0 w-full h-1 bg-primary transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
            
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4">
                <i class="fas fa-boxes text-lg"></i>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">Total Varian Tersedia</p>
            <div class="flex items-baseline gap-1">
                <h3 class="text-2xl font-bold text-slate-800">{{ $data['total_products'] }}</h3>
                <span class="text-xs font-medium text-slate-400">Item Aktif</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-800">Lalu Lintas Barang (7 Hari Terakhir)</h3>
            </div>
            <div class="h-[300px]">
                <canvas id="warehouseChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-800">Baru Saja Masuk</h3>
            </div>
            
            <div class="space-y-0">
                @forelse($recent_activities as $activity)
                <div class="flex items-center gap-4 py-3 border-b border-slate-100 last:border-0">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                        <i class="fas fa-arrow-down text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800">{{ $activity->product->name ?? 'Barang' }}</p>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Dari: {{ $activity->supplier->name ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-emerald-600">+{{ $activity->qty }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($activity->date)->format('d M') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-box"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Belum ada penerimaan barang.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('warehouseChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart_dates) !!},
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: {!! json_encode($chart_in) !!},
                    backgroundColor: '#10b981', // Emerald 500
                    borderRadius: 4,
                    barThickness: 16,
                },
                {
                    label: 'Barang Keluar (Retur/Rusak)',
                    data: {!! json_encode($chart_out) !!},
                    backgroundColor: '#f97316', // Orange 500
                    borderRadius: 4,
                    barThickness: 16,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8, font: { family: 'Poppins' } } },
                tooltip: { backgroundColor: '#1e293b', padding: 12, titleFont: { family: 'Poppins' }, bodyFont: { family: 'Poppins' }, cornerRadius: 8 }
            },
            scales: {
                y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Poppins' } } },
                x: { border: { display: false }, grid: { display: false }, ticks: { font: { family: 'Poppins' } } }
            }
        }
    });
</script>
@endsection