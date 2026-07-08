<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitConversion extends Model
{
    protected $primaryKey = 'id_unit_conversion';
    protected $fillable = ['id_product', 'id_unit', 'multiplier'];

    public function product() { return $this->belongsTo(Product::class, 'id_product'); }
    public function unit() { return $this->belongsTo(Unit::class, 'id_unit'); }
}
