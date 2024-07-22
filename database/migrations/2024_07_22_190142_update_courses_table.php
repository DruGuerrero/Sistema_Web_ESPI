<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('id_year')->after('id_docente');
            $table->dropColumn('id_carrera');

            $table->foreign('id_year')->references('id')->on('years')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('id_carrera')->after('id_docente');
            $table->dropForeign(['id_year']);
            $table->dropColumn('id_year');
        });
    }
}