<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'app_name' => 'PANGLONG-JAYA',
            'address' => 'Jl. Contoh Alamat No. 123, Medan',
            'phone' => '0812-3456-7890',
            'logo_path' => null,
        ]);
    }
}
