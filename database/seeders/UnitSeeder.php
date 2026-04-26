<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            // ==========================================
            // 1. SATUAN DASAR / ECERAN (BASE UNITS)
            // ==========================================
            ['name' => 'Pcs / Biji', 'short_name' => 'Pcs'],
            ['name' => 'Meter', 'short_name' => 'Mtr'],
            ['name' => 'Centimeter', 'short_name' => 'Cm'],
            ['name' => 'Kilogram', 'short_name' => 'Kg'],
            ['name' => 'Gram', 'short_name' => 'Gr'],
            ['name' => 'Batang', 'short_name' => 'Btg'],
            ['name' => 'Lembar / Keping', 'short_name' => 'Lbr'],
            ['name' => 'Kaleng', 'short_name' => 'Klg'],
            
            // ==========================================
            // 2. SATUAN KEMASAN / GROSIR (CONVERSION UNITS)
            // ==========================================
            ['name' => 'Dus / Kotak', 'short_name' => 'Dus'],
            ['name' => 'Sak / Kampil', 'short_name' => 'Sak'],
            ['name' => 'Roll', 'short_name' => 'Roll'],
            ['name' => 'Ikat', 'short_name' => 'Ikat'],
            ['name' => 'Pak / Bungkus', 'short_name' => 'Pak'],
            ['name' => 'Lusin', 'short_name' => 'Lsn'],
            
            // ==========================================
            // 3. SATUAN VOLUME / MUATAN BESAR
            // ==========================================
            ['name' => 'Kubik', 'short_name' => 'M3'],
            ['name' => 'Muatan Truk', 'short_name' => 'Truk'],
            ['name' => 'Muatan Pick-up', 'short_name' => 'Pick-up'],
        ];

        foreach ($units as $unit) {
            // Gunakan firstOrCreate agar tidak terjadi duplikat data jika di-seed ulang
            Unit::firstOrCreate(
                ['short_name' => $unit['short_name']], // Cek berdasarkan short_name
                ['name' => $unit['name']]              // Jika tidak ada, buat baru
            );
        }
    }
}