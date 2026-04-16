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
        
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 transition-all">
            <i class="fas fa-box w-5 text-primary"></i>
            <span class="font-medium">Data Barang</span>
        </a>
        
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 transition-all">
            <i class="fas fa-tags w-5 text-primary"></i>
            <span class="font-medium">Kategori & Satuan</span>
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