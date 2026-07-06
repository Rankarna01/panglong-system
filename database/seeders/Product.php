<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Product;
use App\Models\UnitConversion;
use Illuminate\Support\Str;

class PanglongSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Master Satuan
        $kg = Unit::firstOrCreate(['name' => 'Kilogram', 'short_name' => 'Kg']);
        $dus = Unit::firstOrCreate(['name' => 'Dus / Kotak', 'short_name' => 'Dus']);
        $meter = Unit::firstOrCreate(['name' => 'Meter', 'short_name' => 'Mtr']);
        $roll = Unit::firstOrCreate(['name' => 'Roll Kabel', 'short_name' => 'Roll']);
        $keping = Unit::firstOrCreate(['name' => 'Keping / Lembar', 'short_name' => 'Lbr']);
        $sak = Unit::firstOrCreate(['name' => 'Sak / Kampil', 'short_name' => 'Sak']);

        // 2. Buat Kategori
        $katBesi = Category::firstOrCreate(['name' => 'Besi & Paku']);
        $katListrik = Category::firstOrCreate(['name' => 'Alat Listrik']);
        $katLantai = Category::firstOrCreate(['name' => 'Lantai & Keramik']);
        $katSemen = Category::firstOrCreate(['name' => 'Semen & Pasir']);

        // 3. Buat Produk 1: Paku Payung (Base: Kg -> Konversi: Dus)
        $paku = Product::create([
            'code' => 'BRG-PKU01',
            'name' => 'Paku Payung 2 Inch',
            'category_id' => $katBesi->id,
            'unit_id' => $kg->id, // Base unit: Kg
            'stock' => 150, // Punya 150 Kg di gudang (Setara 5 Dus)
            'min_stock' => 10,
            'price' => 15000, // Harga Rp 15.000 per Kg
        ]);
        // Konversi: 1 Dus = 30 Kg
        UnitConversion::create(['product_id' => $paku->id, 'unit_id' => $dus->id, 'multiplier' => 30]);

        // 4. Buat Produk 2: Kabel Eterna (Base: Meter -> Konversi: Roll)
        $kabel = Product::create([
            'code' => 'BRG-KBL01',
            'name' => 'Kabel Listrik Eterna 2x1.5',
            'category_id' => $katListrik->id,
            'unit_id' => $meter->id, // Base unit: Meter
            'stock' => 200, // Punya 200 Meter di gudang
            'min_stock' => 20,
            'price' => 8000, // Harga Rp 8.000 per Meter
        ]);
        // Konversi: 1 Roll = 50 Meter
        UnitConversion::create(['product_id' => $kabel->id, 'unit_id' => $roll->id, 'multiplier' => 50]);

        // 5. Buat Produk 3: Keramik Roman (Base: Keping -> Konversi: Dus)
        $keramik = Product::create([
            'code' => 'BRG-KRM01',
            'name' => 'Keramik Roman 40x40 Putih Polos',
            'category_id' => $katLantai->id,
            'unit_id' => $keping->id, // Base unit: Keping
            'stock' => 600, // Punya 600 keping
            'min_stock' => 30,
            'price' => 12000, // Harga Rp 12.000 per keping
        ]);
        // Konversi: 1 Dus = 6 Keping
        UnitConversion::create(['product_id' => $keramik->id, 'unit_id' => $dus->id, 'multiplier' => 6]);

        // 6. Buat Produk 4: Semen (Hanya Base Unit: Sak, tanpa konversi)
        Product::create([
            'code' => 'BRG-SMN01',
            'name' => 'Semen Padang 40 Kg',
            'category_id' => $katSemen->id,
            'unit_id' => $sak->id,
            'stock' => 100,
            'min_stock' => 10,
            'price' => 55000,
        ]);
    }
}