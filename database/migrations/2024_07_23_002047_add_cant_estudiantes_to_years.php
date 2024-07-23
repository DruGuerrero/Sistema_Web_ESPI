<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantEstudiantesToYears extends Migration
{
    public function up()
    {
        Schema::table('years', function (Blueprint $table) {
            $table->unsignedInteger('cant_estudiantes')->default(0)->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('years', function (Blueprint $table) {
            $table->dropColumn('cant_estudiantes');
        });
    }
}