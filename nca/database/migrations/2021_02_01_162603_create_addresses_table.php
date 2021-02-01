<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prop_id');
            $table->text('full_address');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->foreignId('province_id')->nullable();
            $table->foreignId('area_id')->nullable();
            $table->foreignId('locality_id')->nullable();
            $table->foreignId('district_id')->nullable();
            $table->foreignId('route_id')->nullable();
            $table->foreignId('metro_id')->nullable();
            $table->foreignId('street_id')->nullable();
            $table->foreignId('house_id')->nullable();
            $table->foreignId('entrance_id')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
