<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
/**
* Run the migrations.
*
* @return void
*/
public function up()
{
Schema::create('advertisements', function (Blueprint $table) {
$table->id();
$table->string('title');
$table->text('description');
$table->decimal('price', 8, 2);
$table->date('rental_start_date')->nullable();
$table->date('rental_end_date')->nullable();
$table->date('expiry_date')->nullable();
$table->timestamps();
});
}

/**
* Reverse the migrations.
*
* @return void
*/
public function down()
{
Schema::dropIfExists('advertisements');
}
}
