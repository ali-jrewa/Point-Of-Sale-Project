<?php

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
        Schema::create('stock_movements', function (Blueprint $table) {
           $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('type');

            /*
             * Positive = Stock In
             * Negative = Stock Out
             */

            $table->integer('quantity');

            /*
             * Stores the related record.
             *
             * Purchase
             * Sale
             * Adjustment
             */

            $table->nullableMorphs('reference');

            $table->text('notes')->nullable();

            $table->timestamp('movement_date');

            $table->timestamps();

            $table->index('type');
            $table->index('movement_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
