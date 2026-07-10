<?php

use App\Enums\ExpenseStatus;
use App\Enums\PaymentMethodStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {

            $table->id();

            $table->foreignId('expense_category_id')
                ->constrained()
                ->cascadeOnDelete();

            // Auto Generated (EXP-000001)
            $table->string('expense_number')->unique();


            $table->string('title');
            $table->text('description')->nullable();

            // Financial Information
            $table->decimal('amount', 12, 2);

            // Date of Expense
            $table->date('expense_date');

            // Payment Information
            $table->string('payment_method')
                ->default(PaymentMethodStatus::Cash->value);

            // Paid To
            $table->string('vendor_name')->nullable();

            // Receipt / Invoice Number
            $table->string('receipt_number')->nullable();

            // Bank Transfer / Check / Transaction Reference
            $table->string('reference_no')->nullable();

            // Expense Status
            $table->string('status')
                ->default(ExpenseStatus::Paid->value);

            // Audit Fields
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('expense_number');
            $table->index('expense_date');
            $table->index('payment_method');
            $table->index('status');
            $table->index('vendor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
