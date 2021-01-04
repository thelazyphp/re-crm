<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id');
            $table->foreignId('inviter_id');
            $table->string('email')->nullable();
            $table->string('token', 64)->unique();
            $table->enum('role', ['owner', 'admin', 'member'])->default('member');
            $table->timestamp('expires_at');
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
        Schema::dropIfExists('org_invitations');
    }
}
