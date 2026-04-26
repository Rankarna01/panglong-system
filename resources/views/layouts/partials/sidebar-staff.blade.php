<div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden transition-opacity opacity-0" onclick="toggleSidebar()"></div>

<aside id="mainSidebar" class="w-72 bg-white border-r min-h-screen flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out h-screen shadow-2xl lg:shadow-none">
    
    <div class="p-6 flex items-center justify-between gap-3 shrink-0">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-primary rounded-lg text-white shadow-sm">
                <i class="fas fa-boxes text-xl"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-primary uppercase">Gudang Inti</span>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-red-500 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 px-4 space-y-1.5 mt-2 overflow-y-auto pb-6">
        
        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-2 mb-2">Main Menu</p>

        <a href="{{ route('gudang.dashboard') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('gudang.dashboard') ? 'sidebar-active shadow-md rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-home w-5 {{ request()->routeIs('gudang.dashboard') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Master Data</p>
        
       <a href="{{ route('gudang.cek-barang.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('gudang.cek-barang.*') ? 'sidebar-active shadow-md bg-primary text-white rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
    <i class="fas fa-box w-5 {{ request()->routeIs('gudang.cek-barang.*') ? 'text-white' : 'text-primary' }}"></i>
    <span class="font-medium">Cek Data Barang</span>
</a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 transition-all text-slate-600 hover:bg-slate-50 rounded-xl">
            <i class="fas fa-truck-loading w-5 text-primary"></i>
            <span class="font-medium">Data Supplier</span>
        </a>

        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Lalu Lintas Stok</p>

        <a href="{{ route('gudang.stok-masuk.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('gudang.stok-masuk.*') ? 'sidebar-active shadow-md rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-arrow-alt-circle-down w-5 {{ request()->routeIs('gudang.stok-masuk.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Stok Masuk (Penerimaan)</span>
        </a>

        <a href="{{ route('gudang.stok-keluar.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('gudang.stok-keluar.*') ? 'sidebar-active shadow-md rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-arrow-alt-circle-up w-5 {{ request()->routeIs('gudang.stok-keluar.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Stok Keluar (Retur/Rusak)</span>
        </a>

       <a href="{{ route('gudang.stok-opname.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('gudang.stok-opname.*') ? 'sidebar-active shadow-md rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-clipboard-check w-5 {{ request()->routeIs('gudang.stok-opname.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Stok Opname</span>
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
            // Buka Sidebar
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        } else {
            // Tutup Sidebar
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }
</script>