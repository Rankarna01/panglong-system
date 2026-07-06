<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice', 'user_id', 'total_amount'];

    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    public function details() { 
        return $this->hasMany(SaleDetail::class); 
    }
}