<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'name',

        'phone',

        'email',

        'address',

        'loyalty_points',

        'is_active',
    ];

    protected $casts = [

        'is_active' => CustomerStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
