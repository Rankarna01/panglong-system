<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $primaryKey = 'id_sale';
    protected $fillable = [
        'invoice', 
        'id_user', 
        'total_amount',
        'subtotal',
        'discount_name',
        'discount_amount',
        'payment_amount',
        'change_amount',
        'payment_method'
    ];

    public function user() { 
        return $this->belongsTo(User::class, 'id_user'); 
    }
    
    public function details() { 
        return $this->hasMany(SaleDetail::class, 'id_sale'); 
    }
}