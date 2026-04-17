<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'description'];

    // Persiapan relasi untuk nanti (Satu supplier bisa punya banyak transaksi stok masuk)
    // public function stockIns() {
    //     return $this->hasMany(StockIn::class);
    // }
}
