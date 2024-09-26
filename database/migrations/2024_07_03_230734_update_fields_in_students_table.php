<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsInStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('num_carnet')->change();
            $table->string('num_celular')->change();
            $table->string('celular_tutor')->nullable()->change();
            $table->dropColumn(['moodle_user', 'moodle_pass']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('num_carnet')->change();
            $table->integer('num_celular')->change();
            $table->integer('celular_tutor')->nullable()->change();
            $table->string('moodle_user')->nullable();
            $table->string('moodle_pass')->nullable();
        });
    }
}