<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_components', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('name');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_components');
    }
}
