<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PurchaseStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->string('purchase_code')->unique();

            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            // Supplier Invoice
            $table->string('invoice_number')->nullable();

            // Purchase Totals
            $table->decimal('subtotal', 12, 2);

            $table->decimal('discount', 12, 2)
                ->default(0);

            $table->decimal('tax', 12, 2)
                ->default(0);

            $table->decimal('total', 12, 2);

            // Purchase Status
            $table->string('purchase_status')->default(PurchaseStatus::Pending->value);
            $table->string('payment_status')->default(PaymentStatus::UnPaid->value);

            $table->text('notes')->nullable();

            $table->date('purchased_at');

            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_number');
            $table->index('purchase_status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
