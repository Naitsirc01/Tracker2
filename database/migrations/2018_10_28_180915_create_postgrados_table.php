<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostgradosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postgrados', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string("nombre");
            $table->string("tipo");
            $table->date("fecha_obtencion");
            $table->integer('persona_id')->unsigned();
            $table->foreign('persona_id')->references('id')
                ->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postgrados');
    }
}
