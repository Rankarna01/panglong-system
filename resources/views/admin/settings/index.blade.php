@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-10">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola identitas dan informasi dasar sistem Panglong.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm flex items-start gap-3 border border-emerald-100">
            <i class="fas fa-check-circle mt-0.5 text-lg"></i><span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm flex items-start gap-3 border border-red-100">
            <i class="fas fa-exclamation-circle mt-0.5 text-lg"></i>
            <ul class="font-medium list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden max-w-3xl">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf

            <div class="flex flex-col md:flex-row gap-6 items-start border-b border-slate-100 pb-8">
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-bold text-slate-800 mb-1">Logo Sistem</label>
                    <p class="text-xs text-slate-500">Gunakan rasio 1:1 (persegi) untuk hasil terbaik. Maksimal 2MB (JPG/PNG/SVG).</p>
                </div>
                <div class="w-full md:w-2/3 flex flex-col md:flex-row gap-4 items-center">
                    <div class="w-24 h-24 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0">
                        @if($setting && $setting->logo_path)
                            <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" class="w-full h-full object-contain">
                        @else
                            <i class="fas fa-warehouse text-3xl text-slate-300"></i>
                        @endif
                    </div>
                    <div class="flex-1 w-full">
                        <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6 items-start border-b border-slate-100 pb-8">
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-bold text-slate-800 mb-1">Nama Sistem</label>
                    <p class="text-xs text-slate-500">Nama ini akan tampil di seluruh sidebar dan kop struk POS.</p>
                </div>
                <div class="w-full md:w-2/3">
                    <input type="text" name="app_name" value="{{ old('app_name', $setting->app_name ?? 'PANGLONG-JAYA') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm font-bold text-slate-800 uppercase" placeholder="Contoh: PANGLONG-JAYA">
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6 items-start border-b border-slate-100 pb-8">
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-bold text-slate-800 mb-1">Alamat Toko</label>
                    <p class="text-xs text-slate-500">Dicetak di bawah nama toko pada struk POS.</p>
                </div>
                <div class="w-full md:w-2/3">
                    <textarea name="address" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700" placeholder="Masukkan alamat lengkap toko...">{{ old('address', $setting->address ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6 items-start">
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-bold text-slate-800 mb-1">Nomor Telepon</label>
                    <p class="text-xs text-slate-500">Dicetak pada struk POS untuk kontak pelanggan.</p>
                </div>
                <div class="w-full md:w-2/3 relative">
                    <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="phone" value="{{ old('phone', $setting->phone ?? '') }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-700 font-medium" placeholder="Contoh: 0812-3456-7890">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-[#4a332c] shadow-md transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
