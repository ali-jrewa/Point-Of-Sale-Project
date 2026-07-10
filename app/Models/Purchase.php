<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PurchaseStatus;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'purchase_code',
        'supplier_id',
        'user_id',
        'invoice_number',
        'subtotal',
        'discount',
        'tax',
        'total',
        'purchase_status',
        'payment_status',
        'notes',
        'purchased_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',

        'purchase_status' => PurchaseStatus::class,

        'payment_status' => PaymentStatus::class,

        'purchased_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
