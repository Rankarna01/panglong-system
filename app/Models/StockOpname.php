<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = ['reference', 'product_id', 'user_id', 'date', 'system_qty', 'actual_qty', 'difference', 'notes'];
    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(User::class); }
}