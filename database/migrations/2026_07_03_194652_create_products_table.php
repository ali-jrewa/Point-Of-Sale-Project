<?php

use App\Enums\ProductStatus;
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
        Schema::create('products', function (Blueprint $table) {
             $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // Core Details
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing & Cost (Using decimal for absolute currency precision)
            $table->decimal('cost_price', 12, 2)->default(0.00);
            $table->decimal('retail_price', 12, 2)->default(0.00);


            // Inventory & Identifiers
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();

            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);

            $table->string('status')->default(ProductStatus::Active->value);

            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('barcode');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
