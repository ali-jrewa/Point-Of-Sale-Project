<?php

use App\Enums\SaleStatus;
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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('invoice_number')->unique();

            $table->decimal('subtotal',12,2);

            $table->decimal('discount',12,2)->default(0);

            $table->decimal('tax',12,2)->default(0);

            $table->decimal('total',12,2);

            $table->string('status')->default(SaleStatus::Pending->value);

            $table->text('notes')->nullable();

            $table->timestamp('sold_at');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
