<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearsTable extends Migration
{
    public function up()
    {
        Schema::create('years', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion')->nullable();
            $table->unsignedBigInteger('id_career');
            $table->timestamps();

            $table->foreign('id_career')->references('id')->on('careers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('years');
    }
}