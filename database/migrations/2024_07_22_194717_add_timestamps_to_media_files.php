<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToMediaFiles extends Migration
{
    public function up()
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
}