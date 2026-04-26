<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'category_id', 'image', 'unit_id', 'stock', 'min_stock', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }


    public function conversions()
    {
        return $this->hasMany(UnitConversion::class);
    }

    public function racks()
    {
        return $this->belongsToMany(Rack::class, 'product_rack')
            ->withPivot('id', 'stock')
            ->withTimestamps();
    }
}
