<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnFormIvfPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ivf_payment', function (Blueprint $table) {
            $table->dropColumn('visit');
            $table->dropColumn('time');
            $table->dropColumn('remaining_day');
            $table->dropColumn('remaining_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ivf_payment', function (Blueprint $table) {
            $table->integer('visit')->nullable()->after('remark');
            $table->time('time')->nullable()->after('visit');
            $table->integer('remaining_day')->nullable()->after('time');
            $table->date('remaining_date')->nullable()->after('remaining_day');
        });
    }
}
