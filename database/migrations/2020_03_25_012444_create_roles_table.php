<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('is_active')->default(1);
            $table->boolean('can_write')->default(1);
            $table->boolean('can_change_user_color')->default(1);
            $table->boolean('can_change_message_color')->default(1);
            $table->boolean('can_send_pics')->default(1);
            $table->boolean('can_make_private_chat')->default(1);
            $table->boolean('is_room_supervisor')->default(0);
        });
        /*Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('uri')->unique();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
