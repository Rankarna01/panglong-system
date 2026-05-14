<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $setting = Setting::first() ?? new Setting();
        
        $setting->app_name = $request->app_name;
        $setting->address = $request->address;
        $setting->phone = $request->phone;

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $path = $request->file('logo')->store('settings', 'public');
            $setting->logo_path = $path;
        }

        $setting->save();

        Cache::forget('global_setting');

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
