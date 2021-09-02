<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientSignupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_signup', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('dob');
            $table->bigInteger('mobile_number');
            $table->bigInteger('other_mobile_number')->nullable();
            $table->text('residence');
            $table->string('main_area');
            $table->string('city');
            $table->integer('state');
            $table->string('reference_doctor')->nullable();
            $table->string('reference_patient')->nullable();
            $table->string('other_reference')->nullable();
            $table->integer('reason')->comment('1=pragnet, 2=no pragnancy, 3= other');
            $table->integer('is_approved');
            $table->integer('approved_by');
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
        Schema::dropIfExists('patients_signup');
    }
}
