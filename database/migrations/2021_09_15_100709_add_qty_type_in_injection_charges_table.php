<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQtyTypeInInjectionChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('injection_charges', function (Blueprint $table) {
            $table->integer('qty_type')->after('quantity')->default('1')->comment('1=qty,2=ml');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('injection_charges', function (Blueprint $table) {
            //
        });
    }
}
