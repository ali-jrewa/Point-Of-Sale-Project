<?php

namespace App\Models;

use App\Enums\ExpenseStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model {
    use SoftDeletes;

    protected $fillable = [

        'expense_category_id',

        'expense_number',

        'title',

        'description',

        'amount',

        'expense_date',

        'payment_method',

        'vendor_name',

        'receipt_number',

        'reference_no',

        'status',

        'created_by',

        'updated_by',

    ];

    protected $casts = [

        'amount' => 'decimal:2',

        'expense_date' => 'date',

        'payment_method' => PaymentMethod::class,

        'status' => ExpenseStatus::class,

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Expense Category
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * User who created the expense.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the expense.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
