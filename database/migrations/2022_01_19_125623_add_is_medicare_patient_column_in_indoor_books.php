<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMedicarePatientColumnInIndoorBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indoor_books', function (Blueprint $table) {
            $table->integer('is_medicare_patient')->default(0)->nullable()->after('is_pediatric_patient');
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
