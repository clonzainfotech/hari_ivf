<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategotyNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patients_id');
            $table->dateTime('date');
            $table->date('reminder_date');
            $table->string('message');
            $table->integer('category_id');
            $table->string('read_by')->nullable();
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
        Schema::dropIfExists('category_notifications');
    }
}
