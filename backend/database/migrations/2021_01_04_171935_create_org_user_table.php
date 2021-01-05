<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id');
            $table->foreignId('user_id');
            $table->enum('role', ['owner', 'admin', 'member'])->default('member');
            $table->timestamps();
            $table->unique(['org_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_user');
    }
}
