<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIvfPaymentReminderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ivf_payment_reminder', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patients_id');
            $table->date('date');
            $table->integer('category')->nullable();
            $table->bigInteger('payment');
            $table->integer('status');
            $table->integer('updated_by')->default(null);
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
        Schema::dropIfExists('ivf_payment_reminder');
    }
}
