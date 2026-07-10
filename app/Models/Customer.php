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

        'customer_code',

        'first_name',

        'last_name',

        'company_name',

        'email',

        'phone',

        'date_of_birth',

        'address',

        'credit_limit',

        'status',

        'notes',

        'created_by',

        'updated_by',
    ];


    protected $casts = [

        'status' => CustomerStatus::class,

         'date_of_birth'=>'date',
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
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function updater()
    {
        return $this->belongsTo(User::class,'updated_by');
}
}
