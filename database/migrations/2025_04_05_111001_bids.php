<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->timestamps();

            $table->index(['advertisement_id', 'amount']);
            $table->index(['user_id', 'advertisement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
