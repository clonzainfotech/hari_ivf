<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldForInjectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('injections', function (Blueprint $table) {
            $table->integer('category')->default(1)->after('name')->nullable()->comment('1=iui,2=ivf');
            $table->integer('net_price')->default(0)->after('category')->nullable()->comment('injection price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
