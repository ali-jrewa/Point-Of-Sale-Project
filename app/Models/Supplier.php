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

        'first_name',

        'last_name',

        'company_name',

        'phone',

        'email',

        'address',

        'tax_number',

        'status',

        'created_by',

        'updated_by',
    ];

    protected $casts = [

        'status' => SupplierStatus::class,
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
