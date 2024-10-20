<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechnungdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('rechnungdetails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rechnungId');
            $table->date('date');
            $table->integer('mitarbeiter');
            $table->string('bezeichnung');
            $table->time('von');
            $table->time('bis');
            $table->string('pause');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rechnungdetails');
    }
}
