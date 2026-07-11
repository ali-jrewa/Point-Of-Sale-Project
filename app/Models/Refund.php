<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = ['sale_id','user_id','refund_code','amount','method','reason','refunded_at'];
    protected $casts = ['refunded_at' => 'datetime'];

    public function sale() { return $this->belongsTo(Sale::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(RefundItem::class); }
}
