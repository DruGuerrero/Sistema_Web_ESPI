<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCareerToMediaFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->unsignedBigInteger('id_career')->nullable()->after('id_course');

            // Si tienes una relación definida entre media_files y careers, puedes agregar la clave foránea de la siguiente manera
            $table->foreign('id_career')->references('id')->on('careers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_files', function (Blueprint $table) {
            // Si se agregó una clave foránea, necesitarás eliminarla antes de eliminar la columna
            $table->dropForeign(['id_career']);
            $table->dropColumn('id_career');
        });
    }
}