<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisement_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->comment('Rating from 1-5');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Ensure a user can only review an advertisement once
            $table->unique(['user_id', 'advertisement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisement_reviews');
    }
};
