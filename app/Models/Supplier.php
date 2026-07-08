<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'id_supplier';
    protected $fillable = ['name', 'phone', 'address', 'description'];

    public function stockIns() { return $this->hasMany(StockIn::class, 'id_supplier'); }
}
