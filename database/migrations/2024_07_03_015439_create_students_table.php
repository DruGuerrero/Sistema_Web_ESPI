<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->integer('num_carnet');
            $table->string('email')->unique();
            $table->string('ciudad_domicilio');  // No nullable
            $table->integer('num_celular');
            $table->string('moodle_user')->nullable();
            $table->string('moodle_pass')->nullable();
            $table->string('matricula', 3);
            $table->string('nombre_tutor')->nullable();
            $table->integer('celular_tutor')->nullable();
            $table->string('ciudad_tutor')->nullable();
            $table->string('parentesco')->nullable();
            $table->boolean('disabled')->default(0);
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
        Schema::dropIfExists('students');
    }
}
