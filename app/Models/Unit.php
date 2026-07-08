<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $primaryKey = 'id_unit';
    protected $fillable = ['name', 'short_name'];

    public function products() { return $this->hasMany(Product::class, 'id_unit'); }
}
