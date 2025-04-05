<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisement_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->string('type')->default('purchase');
            $table->string('status')->default('sold');
            $table->boolean('returned')->default(false);
            $table->dateTime('return_date')->nullable();
            $table->string('return_photo')->nullable();
            $table->decimal('calculated_wear', 8, 2)->nullable();
            $table->integer('rental_days')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisement_transactions');
    }
};
