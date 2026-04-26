<header class="h-16 bg-white border-b flex items-center justify-between px-4 md:px-8 sticky top-0 z-40 shadow-sm md:shadow-none">
    
    <div class="flex items-center gap-3 md:gap-4">
        
        <button onclick="toggleSidebar()" class="md:hidden w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center hover:bg-primary hover:text-white active:scale-95 transition-all">
            <i class="fas fa-bars"></i>
        </button>

        <h2 class="font-semibold text-base md:text-lg text-primary capitalize truncate max-w-[180px] sm:max-w-xs md:max-w-none">
            {{ str_replace('.', ' ', Route::currentRouteName()) }}
        </h2>
    </div>
    
    <div class="relative" id="profileMenu">
        
        <div class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 p-1.5 pr-3 rounded-xl transition-all duration-300" onclick="toggleProfileDropdown()">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-semibold">{{ Auth::user()->role }}</p>
            </div>
            
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-white font-bold shadow-sm border-2 border-white ring-2 ring-slate-100">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-300" id="dropdownArrow"></i>
            </div>
        </div>

        <div id="profileDropdown" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 hidden flex-col overflow-hidden z-50 transform origin-top-right transition-all">
            
            <div class="px-4 py-3 border-b border-slate-50 md:hidden bg-slate-50/50">
                <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-primary uppercase tracking-widest font-bold">{{ Auth::user()->role }}</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3.5 text-sm text-red-600 font-bold hover:bg-red-50 hover:text-red-700 flex items-center gap-3 transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    Keluar Sistem
                </button>
            </form>
            
        </div>
    </div>
</header>

<script>
    // Fungsi untuk membuka/tutup dropdown profil
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('dropdownArrow');
        
        dropdown.classList.toggle('hidden');
        dropdown.classList.toggle('flex');
        
        if(dropdown.classList.contains('hidden')) {
            arrow.style.transform = 'rotate(0deg)';
        } else {
            arrow.style.transform = 'rotate(180deg)';
        }
    }

    // Menutup dropdown jika klik di luar area
    document.addEventListener('click', function(event) {
        const profileMenu = document.getElementById('profileMenu');
        const dropdown = document.getElementById('profileDropdown');
        const arrow = document.getElementById('dropdownArrow');

        if (profileMenu && dropdown && arrow) {
            if (!profileMenu.contains(event.target) && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('flex');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    });
</script>