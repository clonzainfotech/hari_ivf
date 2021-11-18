<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIvfPkgIdInIndoorDeposites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indoor_deposits', function (Blueprint $table) {
            $table->integer('ivf_payment_id')->nullable()->after('package');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indoor_deposits', function (Blueprint $table) {
            //
        });
    }
}
