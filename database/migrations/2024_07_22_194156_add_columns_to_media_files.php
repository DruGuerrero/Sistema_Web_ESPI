<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMediaFiles extends Migration
{
    public function up()
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable()->after('id');
            $table->unsignedBigInteger('id_course')->nullable()->after('student_id');
            $table->string('type')->after('id_course');
            $table->string('file')->after('type');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('id_course')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['id_course']);
            $table->dropColumn('student_id');
            $table->dropColumn('id_course');
            $table->dropColumn('type');
            $table->dropColumn('file');
        });
    }
}