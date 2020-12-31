<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_managers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('type')->nullable();
            $table->string('kod')->nullable();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('sname')->nullable();
            $table->text('phones')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('crm_managers');
    }
}
