<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnNameInInjectionManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('injection_manager', function (Blueprint $table) {
            $table->renameColumn('inj_charge_id', 'injection');
            $table->integer('visit')->default(0)->after('plan')->nullable()->comment('iui and ivf visit number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('injection_manager', function (Blueprint $table) {
            //
        });
    }
}
