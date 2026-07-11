<?php

use App\Enums\PaymentMethod;
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
        Schema::create('payments', function (Blueprint $table) {
             $table->id();

            $table->foreignId('sale_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
            ->constrained()
            ->restrictOnDelete();

            $table->string('payment_code')->unique();

            $table->string('method')->default(PaymentMethod::Cash->value);

            $table->decimal('amount',12,2);

            $table->string('reference')->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('paid_at');

            $table->timestamps();
            $table->softDeletes();


            $table->index('paid_at');
            $table->index('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
