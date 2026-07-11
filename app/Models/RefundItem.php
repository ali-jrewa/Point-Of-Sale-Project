<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundItem extends Model
{
    protected $fillable = ['refund_id','sale_item_id','product_id','quantity','amount','restocked'];

    public function saleItem() { return $this->belongsTo(SaleItem::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
