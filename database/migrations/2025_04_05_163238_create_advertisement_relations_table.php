<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementRelationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('advertisement_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('related_advertisement_id')->constrained('advertisements')->cascadeOnDelete();
            $table->timestamps();

            // Using a shorter custom index name
            $table->unique(
                ['advertisement_id', 'related_advertisement_id'],
                'ad_relations_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisement_relations');
    }
}
