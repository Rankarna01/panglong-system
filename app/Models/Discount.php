<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $primaryKey = 'id_discount';
    
    protected $fillable = [
        'name',
        'type',
        'value',
        'is_active',
    ];
}
