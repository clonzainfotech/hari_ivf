<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInjectionManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('injection_manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('patients_id');
            $table->bigInteger('type')->comment('1=hormon,2=ivf,3=iui');
            $table->integer('inj_charge_id');
            $table->integer('net_price');
            $table->integer('amount');
            $table->integer('cycle_no')->nullable();
            $table->integer('plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('injection_manager');
    }
}
