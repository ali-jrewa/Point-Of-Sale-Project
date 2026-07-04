<?php

namespace App\Models;

use App\Enums\SupplierStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'name',

        'company',

        'phone',

        'email',

        'address',

        'tax_number',

        'is_active',
    ];

    protected $casts = [

        'is_active' => SupplierStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
