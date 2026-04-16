<header class="h-16 bg-white border-b flex items-center justify-between px-8 sticky top-0 z-40">
    <div class="flex items-center gap-4">
        <h2 class="font-semibold text-lg text-primary capitalize">
            {{ str_replace('.', ' ', Route::currentRouteName()) }}
        </h2>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="text-right hidden md:block">
            <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-500 uppercase tracking-widest">{{ Auth::user()->role->name }}</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-white font-bold shadow-md">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
    </div>
</header>