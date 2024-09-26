<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Eliminar la restricción de clave externa en 'id_docente'
            $table->dropForeign(['id_docente']);

            // Eliminar el índice único en 'id_docente'
            $table->dropUnique('courses_id_docente_unique');

            // Volver a agregar la restricción de clave externa en 'id_docente'
            $table->foreign('id_docente')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Eliminar la restricción de clave externa en 'id_docente'
            $table->dropForeign(['id_docente']);

            // Restaurar el índice único en 'id_docente'
            $table->unique('id_docente');

            // Volver a agregar la restricción de clave externa en 'id_docente'
            $table->foreign('id_docente')->references('id')->on('users')->onDelete('cascade');
        });
    }
}