<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('gelds', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('mitarbeiterId');
            $table->string('amount');
            $table->string('month');
            $table->string('note');
            $table->integer('status')->default(1);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gelds');
    }
}
