<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitarbeiterstundesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('mitarbeiterstundes', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('mitarbeiterId');
            $table->integer('festivalId');
            $table->integer('bezeichnungId');
            $table->time('beginn');
            $table->time('ende');
            $table->string('pause')->default('0');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mitarbeiterstundes');
    }
}
