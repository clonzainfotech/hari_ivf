<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryFieldInInjectionChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('injection_charges', function (Blueprint $table) {
            $table->integer('type')->default(0)->after('quantity')->comment('1=hormon,2=ivf,3=iui');
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
