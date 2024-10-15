<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCourseToMediaFilesTable extends Migration
{
    public function up()
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->unsignedBigInteger('id_course')->nullable()->after('student_id');
            
            // Añadir clave foránea
            $table->foreign('id_course')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('media_files', function (Blueprint $table) {
            // Eliminar la clave foránea y la columna
            $table->dropForeign(['id_course']);
            $table->dropColumn('id_course');
        });
    }
}