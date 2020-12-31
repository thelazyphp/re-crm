<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJandexGeolocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jandex_geolocations', function (Blueprint $table) {
            $table->unsignedBigInteger('crm_object_id')->primary();
            $table->string('long')->nullable();
            $table->string('lat')->nullable();
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('area')->nullable();
            $table->string('locality')->nullable();
            $table->string('district')->nullable();
            $table->string('street')->nullable();
            $table->string('house')->nullable();
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
        Schema::dropIfExists('jandex_geolocations');
    }
}
