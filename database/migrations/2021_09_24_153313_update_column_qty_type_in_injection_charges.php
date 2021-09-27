<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnQtyTypeInInjectionCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('injection_charges', function (Blueprint $table) {
            $table->string('qty_type')->default(1)->comment('1=QTY,2=VIAL')->change();
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
