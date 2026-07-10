<?php

use App\Enums\CustomerStatus;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('customer_code')->unique();

            $table->string('first_name');
            $table->string('last_name');

            $table->string('company_name')->nullable();

            $table->string('email')->nullable()->unique();
            $table->string('phone')->unique();


            $table->date('date_of_birth')->nullable();

            $table->text('address')->nullable();

            $table->decimal('credit_limit',12,2)
            ->default(0);

            $table->integer('reward_points')
            ->default(0)->nullable();

            $table->string('status')->default(CustomerStatus::Inactive->value);;

            $table->text('notes')->nullable();

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

            $table->index('first_name');
            $table->index('last_name');
            $table->index('email');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
