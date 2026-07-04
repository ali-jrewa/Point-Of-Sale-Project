<?php

use App\Enums\DiscountType;
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
        Schema::create('discounts', function (Blueprint $table) {
             $table->id();

            $table->string('name');

            $table->string('type');

            $table->decimal('value',12,2);

            $table->dateTime('starts_at');

            $table->dateTime('ends_at')->nullable();

            $table->string('is_active')->default(DiscountType::Fixed->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
