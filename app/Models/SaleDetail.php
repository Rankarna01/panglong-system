<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $primaryKey = 'id_sale_detail';
    protected $fillable = ['id_sale', 'id_product', 'qty', 'price', 'subtotal'];

    public function product() { 
        return $this->belongsTo(Product::class, 'id_product'); 
    }
    
    public function sale() { 
        return $this->belongsTo(Sale::class, 'id_sale'); 
    }
}
