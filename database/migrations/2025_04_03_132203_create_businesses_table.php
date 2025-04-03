<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('url', 64)->nullable();
            $table->timestamps();

            $table->unique('url', 'businesses_url_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
