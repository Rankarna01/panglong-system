<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $primaryKey = 'id_stock_out';
    protected $fillable = ['reference', 'id_product', 'id_user', 'qty', 'date', 'reason'];

    public function product() { return $this->belongsTo(Product::class, 'id_product'); }
    public function user() { return $this->belongsTo(User::class, 'id_user'); }
}
