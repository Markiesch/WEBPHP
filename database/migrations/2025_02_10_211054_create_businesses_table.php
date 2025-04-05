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
            $table->string('name', 255);
            $table->string('url', 255);

            // Contract management fields
            $table->string('contract_status')->default('pending');
            $table->string('contract_file')->nullable();
            $table->timestamp('contract_updated_at')->nullable();
            $table->timestamp('contract_reviewed_at')->nullable();
            $table->unsignedBigInteger('contract_reviewed_by')->nullable();
            $table->foreign('contract_reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->text('contract_rejection_reason')->nullable();

            $table->timestamps();

            $table->unique('url', 'businesses_url_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
