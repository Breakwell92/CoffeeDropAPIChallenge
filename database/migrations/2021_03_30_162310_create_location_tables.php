<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('postcode');
            $table->float('latitude', 11, 8)->nullable();
            $table->float('longitude', 11, 8)->nullable();

            $table->timestamps();
        });

        Schema::create('location_opening_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->string('open_time');
            $table->string('closed_time');
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::create('cashback_calculation_requests', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('remote_addr');
            $table->string('user_agent');
            $table->json('input');
            $table->integer('cashback_value');
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
        Schema::table('location_opening_times', function (Blueprint $table) {
            $table->dropForeign('location_id');
        });

        Schema::dropIfExists('locations');
        Schema::dropIfExists('location_opening_times');
        Schema::dropIfExists('cashback_calculation_requests');

    }
}
