<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarbonFootprints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carbon_footprints', function (Blueprint $table) {
            $table->id();
            $table->float('activity', 8, 2);
            $table->string('activityType', 10);
            $table->string('country', 3);
            $table->string('fuelType', 50)->nullable();
            $table->string('mode', 50)->nullable();
            $table->float('footprint', 8, 2);
            $table->string('appTKN', 50)->nullable();
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
        Schema::dropIfExists('carbon_footprints');
    }
}
