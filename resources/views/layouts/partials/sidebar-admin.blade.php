<aside class="w-72 bg-white border-r min-h-screen flex flex-col sticky top-0 h-screen">
    <div class="p-6 flex items-center gap-3 shrink-0">
        <div class="p-2 bg-primary rounded-lg text-white shadow-sm">
            <i class="fas fa-warehouse text-xl"></i>
        </div>
        <span class="text-xl font-bold tracking-tight text-primary uppercase">PANGLONG-JAYA</span>
    </div>

    <nav class="flex-1 px-4 space-y-1.5 mt-2 overflow-y-auto pb-6">

        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-2 mb-2">Main Menu</p>

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.dashboard') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-home w-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Dashboard</span>
        </a>


        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Inventory Management</p>

        <a href="{{ route('admin.barang.index') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.barang.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-box w-5 {{ request()->routeIs('admin.barang.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Data Barang</span>
        </a>

        <a href="{{ route('admin.kategori.index') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.kategori.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i class="fas fa-tags w-5 {{ request()->routeIs('admin.kategori.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Kategori Barang</span>
        </a>

        <a href="{{ route('admin.satuan.index') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.satuan.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i
                class="fas fa-balance-scale w-5 {{ request()->routeIs('admin.satuan.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Master Satuan</span>
        </a>

        <a href="{{ route('admin.rak.index') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.rak.index') ? 'sidebar-active shadow-md bg-primary text-white rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i
                class="fas fa-pallet w-5 {{ request()->routeIs('admin.rak.index') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Master Rak</span>
        </a>

        <a href="{{ route('admin.rak.monitoring') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.rak.monitoring') ? 'sidebar-active shadow-md bg-primary text-white rounded-xl' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i
                class="fas fa-binoculars w-5 {{ request()->routeIs('admin.rak.monitoring') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Monitoring Rak</span>
        </a>

        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Manajemen Stok</p>

        <a href="{{ route('admin.supplier.index') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.supplier.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i
                class="fas fa-truck-loading w-5 {{ request()->routeIs('admin.supplier.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Manajemen Supplier</span>
        </a>


        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Pusat Laporan</p>

        @php
            // Logika untuk mengecek apakah salah satu menu laporan sedang aktif
            $isLaporanActive =
                request()->routeIs('admin.laporan.*') ||
                request()->routeIs('admin.stok-masuk.*') ||
                request()->routeIs('admin.stok-keluar.*');
        @endphp

        <button onclick="toggleDropdown('dropdown-laporan', 'arrow-laporan')"
            class="w-full flex items-center justify-between px-4 py-3 transition-all text-slate-600 hover:bg-slate-50 rounded-xl focus:outline-none">
            <div class="flex items-center gap-3">
                <i class="fas fa-folder-open w-5 text-primary"></i>
                <span class="font-medium">Laporan Gudang</span>
            </div>
            <i id="arrow-laporan"
                class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isLaporanActive ? 'rotate-180' : '' }}"></i>
        </button>

        <div id="dropdown-laporan"
            class="{{ $isLaporanActive ? 'flex' : 'hidden' }} flex-col mt-1 space-y-1 bg-slate-50/50 rounded-xl py-2 relative before:absolute before:left-6 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">

            <a href="{{ route('admin.laporan.stok-opname') }}"
                class="flex items-center gap-3 pl-10 pr-4 py-2.5 transition-all text-sm {{ request()->routeIs('admin.laporan.stok-opname') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary hover:bg-slate-100/50' }}">
                <i
                    class="fas fa-circle text-[6px] {{ request()->routeIs('admin.laporan.stok-opname') ? 'text-primary' : 'text-slate-300' }}"></i>
                Lap. Stok Opname
            </a>

            <a href="{{ route('admin.laporan.stok') }}"
                class="flex items-center gap-3 pl-10 pr-4 py-2.5 transition-all text-sm {{ request()->routeIs('admin.laporan.stok') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary hover:bg-slate-100/50' }}">
                <i
                    class="fas fa-circle text-[6px] {{ request()->routeIs('admin.laporan.stok') ? 'text-primary' : 'text-slate-300' }}"></i>
                Lap. Sisa Stok
            </a>

            <a href="{{ route('admin.laporan.penjualan') }}"
                class="flex items-center gap-3 pl-10 pr-4 py-2.5 transition-all text-sm {{ request()->routeIs('admin.laporan.penjualan') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary hover:bg-slate-100/50' }}">
                <i
                    class="fas fa-circle text-[6px] {{ request()->routeIs('admin.laporan.penjualan') ? 'text-primary' : 'text-slate-300' }}"></i>
                Lap. Penjualan
            </a>

            <a href="{{ route('admin.stok-masuk.index') }}"
                class="flex items-center gap-3 pl-10 pr-4 py-2.5 transition-all text-sm {{ request()->routeIs('admin.stok-masuk.*') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary hover:bg-slate-100/50' }}">
                <i
                    class="fas fa-circle text-[6px] {{ request()->routeIs('admin.stok-masuk.*') ? 'text-primary' : 'text-slate-300' }}"></i>
                Lap. Stok Masuk
            </a>

            <a href="{{ route('admin.stok-keluar.index') }}"
                class="flex items-center gap-3 pl-10 pr-4 py-2.5 transition-all text-sm {{ request()->routeIs('admin.stok-keluar.*') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary hover:bg-slate-100/50' }}">
                <i
                    class="fas fa-circle text-[6px] {{ request()->routeIs('admin.stok-keluar.*') ? 'text-primary' : 'text-slate-300' }}"></i>
                Lap. Stok Keluar
            </a>
        </div>


        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Transaksi</p>

        <a href="{{ route('admin.riwayat.penjualan') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.riwayat.penjualan') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i
                class="fas fa-receipt w-5 {{ request()->routeIs('admin.riwayat.penjualan') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Riwayat Penjualan</span>
        </a>


        <p class="text-[10px] font-bold text-slate-400 px-3 uppercase tracking-[2px] mt-6 mb-2">Pengaturan</p>

        <a href="{{ route('admin.user.index') }}"
            class="flex items-center gap-3 px-4 py-3 transition-all {{ request()->routeIs('admin.user.*') ? 'sidebar-active shadow-md' : 'text-slate-600 hover:bg-slate-50 rounded-xl' }}">
            <i
                class="fas fa-users-cog w-5 {{ request()->routeIs('admin.user.*') ? 'text-white' : 'text-primary' }}"></i>
            <span class="font-medium">Manajemen User</span>
        </a>
    </nav>

    <div class="p-4 border-t shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 py-3 rounded-xl font-semibold hover:bg-red-100 transition-all">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<script>
    function toggleDropdown(dropdownId, arrowId) {
        const dropdown = document.getElementById(dropdownId);
        const arrow = document.getElementById(arrowId);

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            dropdown.classList.add('flex');
            arrow.classList.add('rotate-180');
        } else {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('flex');
            arrow.classList.remove('rotate-180');
        }
    }
</script>
