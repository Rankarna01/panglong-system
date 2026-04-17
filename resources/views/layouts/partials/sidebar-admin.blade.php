<aside class="w-72 bg-white border-r min-h-screen flex flex-col sticky top-0 h-screen">
    <div class="p-6 flex items-center gap-3">
        <div class="p-2 bg-primary rounded-lg text-white">
            <i class="fas fa-warehouse text-xl"></i>
        </div>
        <span class="text-xl font-bold tracking-tight text-primary uppercase">FlowInti</span>
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mb-2">Main Menu</p>
        
        <a href="#" class="sidebar-active flex items-center gap-3 px-4 py-3">
            <i class="fas fa-home w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Inventory Management</p>
       <a href="{{ route('admin.barang.index') }}" 
   class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.barang.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
    <i class="fas fa-box w-5 {{ request()->routeIs('admin.barang.*') ? 'text-white' : 'text-primary' }}"></i>
    <span class="font-medium">Data Barang</span>
</a>
        
        <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.kategori.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-tags w-5 {{ request()->routeIs('admin.kategori.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Kategori Barang</span>
        </a>

        <a href="{{ route('admin.satuan.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.satuan.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-balance-scale w-5 {{ request()->routeIs('admin.satuan.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Master Satuan</span>
        </a>

        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Manajemen Stok</p>

        <a href="{{ route('admin.supplier.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.supplier.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-truck-loading w-5 {{ request()->routeIs('admin.supplier.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Manajemen Supplier</span>
        </a>
        <a href="{{ route('admin.stok-masuk.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.stok-masuk.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-file-invoice w-5 {{ request()->routeIs('admin.stok-masuk.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Lap. Stok Masuk</span>
        </a>

        <a href="{{ route('admin.stok-keluar.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.stok-keluar.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fas fa-file-export w-5 {{ request()->routeIs('admin.stok-keluar.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Lap. Stok Keluar</span>
        </a>

        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Transaction</p>
        
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 transition-all">
            <i class="fas fa-cash-register w-5 text-primary"></i>
            <span class="font-medium">Point of Sales</span>
        </a>
    </nav>

    <div class="p-4 border-t">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 py-3 rounded-xl font-semibold hover:bg-red-100 transition-all">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>