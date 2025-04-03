<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->unsignedTinyInteger('wear_percentage')->default(0);
            $table->string('image_url');
            $table->enum('type', ['sale', 'rental'])->default('sale');
            $table->date('rental_start_date')->nullable();
            $table->date('rental_end_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
