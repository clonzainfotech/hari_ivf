<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPediatricPatientColumnInIndoorBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indoor_books', function (Blueprint $table) {
            $table->integer('is_pediatric_patient')->nullable()->after('procedure_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indoor_books', function (Blueprint $table) {
            //
        });
    }
}
