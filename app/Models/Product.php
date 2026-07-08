<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'id_product';
    protected $fillable = ['code', 'name', 'id_category', 'image', 'id_unit', 'stock', 'min_stock', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id_unit');
    }

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'id_unit', 'id_unit');
    }


    public function conversions()
    {
        return $this->hasMany(UnitConversion::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse', 'id_product', 'id_warehouse')
            ->withPivot('id_product_warehouse', 'stock')
            ->withTimestamps();
    }
}
