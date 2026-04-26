<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    // Relasi balik ke Produk
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_rack')
            ->withPivot('id', 'stock')
            ->withTimestamps();
    }
}
