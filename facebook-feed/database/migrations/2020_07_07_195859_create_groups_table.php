<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('url')->unique();
            $table->boolean('is_public');
            $table->boolean('is_visible');
            $table->text('image')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_parsing')->default(false);
            $table->boolean('get_posts')->default(true);
            $table->unsignedInteger('posts_limit')->default(100);
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
        Schema::dropIfExists('groups');
    }
}
