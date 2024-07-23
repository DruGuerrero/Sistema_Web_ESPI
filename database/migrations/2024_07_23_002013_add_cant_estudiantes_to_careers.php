<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantEstudiantesToCareers extends Migration
{
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->unsignedInteger('cant_estudiantes')->default(0)->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn('cant_estudiantes');
        });
    }
}