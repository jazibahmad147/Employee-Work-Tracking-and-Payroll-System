<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitarbeitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('mitarbeiters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vorname');
            $table->string('nachname');
            $table->date('geburtsdatum');
            $table->string('geburtsort');
            $table->string('handynummer');
            $table->string('anschrift');
            $table->string('rate')->default('9');
            $table->string('mitarbeiterStatus')->default('0');
            $table->string('arbeitszeit')->default('0');
            $table->string('arbeitszeitGehalt')->default('0');
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
        Schema::dropIfExists('mitarbeiters');
    }
}
