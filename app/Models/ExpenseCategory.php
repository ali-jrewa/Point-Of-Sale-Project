<?php

namespace App\Models;

use App\Enums\ExpenseCategoryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'name',

        'code',

        'description',

        'status'

    ];

    protected $casts = [

        'deleted_at'=>'datetime',

        'status' => ExpenseCategoryStatus::class,

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
