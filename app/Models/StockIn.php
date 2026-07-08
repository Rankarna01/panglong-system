<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $primaryKey = 'id_stock_in';
    protected $fillable = ['reference', 'id_supplier', 'id_product', 'id_user', 'qty', 'date', 'payment_method', 'notes'];

    public function supplier() { return $this->belongsTo(Supplier::class, 'id_supplier'); }
    public function product() { return $this->belongsTo(Product::class, 'id_product'); }
    public function user() { return $this->belongsTo(User::class, 'id_user'); }
}
