<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_objects', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('transaction')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->boolean('disabled')->default(false);
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->string('lot')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedTinyInteger('ocenka')->nullable();
            $table->string('vid')->nullable();
            $table->string('label')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->string('price_per_sqm')->nullable();
            $table->string('currency')->nullable();
            $table->unsignedInteger('pricenow')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('realcity')->nullable();
            $table->string('street')->nullable();
            $table->string('microdistrict')->nullable();
            $table->string('num_home')->nullable();
            $table->text('gps_coordinats')->nullable();
            $table->string('area_snb')->nullable();
            $table->string('area')->nullable();
            $table->string('living_space')->nullable();
            $table->string('kitchen_area')->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->string('wall_material')->nullable();
            $table->string('MATROOF')->nullable();
            $table->unsignedTinyInteger('floor_apartment')->nullable();
            $table->unsignedTinyInteger('number_of_floors')->nullable();
            $table->unsignedTinyInteger('rooms')->nullable();
            $table->string('bathroom')->nullable();
            $table->string('balcony')->nullable();
            $table->string('land_area')->nullable();
            $table->string('accomplishment')->nullable();
            $table->string('outbuildings')->nullable();
            $table->string('road')->nullable();
            $table->boolean('newflat')->default(false);
            $table->string('electricity')->nullable();
            $table->string('heating')->nullable();
            $table->string('water_supply')->nullable();
            $table->string('sewerage')->nullable();
            $table->text('images')->nullable();
            $table->timestamps();
            $table->timestamp('realt_published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_objects');
    }
}
