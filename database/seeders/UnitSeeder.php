<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Pcs/Biji', 'short_name' => 'pcs'],
            ['name' => 'Sak', 'short_name' => 'sak'],
            ['name' => 'Meter', 'short_name' => 'm'],
            ['name' => 'Kubik', 'short_name' => 'm3'],
            ['name' => 'Batang', 'short_name' => 'btg'],
            ['name' => 'Kilogram', 'short_name' => 'kg'],
            ['name' => 'Lembar', 'short_name' => 'lbr'],
            ['name' => 'Roll', 'short_name' => 'roll'],
            ['name' => 'Kaleng', 'short_name' => 'klg'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
