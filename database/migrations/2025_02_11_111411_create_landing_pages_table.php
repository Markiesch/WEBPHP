<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingPagesTable extends Migration
{
    public function up()
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->text('components')->nullable(); // JSON field to store components
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('landing_pages');
    }
}
