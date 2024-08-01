<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsInPaymentsAndProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('monto_pagado', 8, 2)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('precio', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('monto_pagado')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('precio')->change();
        });
    }
}