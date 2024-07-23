<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdMoodleToCareers extends Migration
{
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->unsignedBigInteger('id_moodle')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn('id_moodle');
        });
    }
}