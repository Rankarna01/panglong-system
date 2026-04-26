<div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden transition-opacity opacity-0" onclick="toggleSidebar()"></div>

<aside id="mainSidebar" class="w-72 bg-white border-r min-h-screen flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out h-screen shadow-2xl lg:shadow-none">
    
    <div class="p-6 flex items-center justify-between gap-3 shrink-0">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-primary rounded-lg text-white shadow-sm">
                <i class="fas fa-cash-register text-xl"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-primary uppercase">Kasir Inti</span>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-red-500 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 px-4 space-y-1.5 mt-2 overflow-y-auto pb-6">
        
        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-2 mb-2">Kasir (POS)</p>

        <a href="{{ route('kasir.pos.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('kasir.pos.*') ? 'sidebar-active shadow-md rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-shopping-cart w-5 {{ request()->routeIs('kasir.pos.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Transaksi Baru</span>
        </a>

       <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Riwayat</p>

        <a href="{{ route('kasir.riwayat-penjualan') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('kasir.riwayat-penjualan') ? 'sidebar-active shadow-md rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-receipt w-5 {{ request()->routeIs('kasir.riwayat-penjualan') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Riwayat Penjualan Saya</span>
        </a>

    </nav>

    <div class="p-4 border-t shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 py-3 rounded-xl font-semibold hover:bg-red-100 transition-all">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }
</script>