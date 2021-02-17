<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('category_id')->index();
            $table->unsignedTinyInteger('type_id')->index();
            $table->unsignedTinyInteger('source_id')->index();
            $table->text('url');
            $table->text('title')->nullable();
            $table->text('images')->nullable();
            $table->text('full_address')->nullable();
            $table->foreignId('seller_id')->nullable();
            $table->unsignedTinyInteger('rooms')->nullable();
            $table->unsignedTinyInteger('floor')->nullable();
            $table->unsignedTinyInteger('floors')->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->float('land_size')->nullable();
            $table->float('total_size')->nullable();
            $table->float('living_size')->nullable();
            $table->float('kitchen_size')->nullable();
            $table->string('roof')->nullable();
            $table->string('walls')->nullable();
            $table->string('balcony')->nullable();
            $table->string('bathroom')->nullable();
            $table->string('price_currency')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->float('price_sqm')->nullable();
            $table->boolean('checked')->default(false);
            $table->timestamps();
            $table->timestamp('published_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
