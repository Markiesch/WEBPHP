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
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('starting_price', 8, 2)->nullable();
            $table->decimal('current_bid', 8, 2)->nullable();
            $table->unsignedTinyInteger('wear_percentage')->default(0);
            $table->decimal('wear_per_day', 8, 2)->nullable();
            $table->string('image_url');
            $table->enum('type', ['sale', 'rental', 'auction'])->default('sale');
            $table->date('rental_start_date')->nullable();
            $table->date('rental_end_date')->nullable();
            $table->dateTime('auction_end_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
