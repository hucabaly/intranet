<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            return;
        }
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nickname');
            $table->string('email')->unique();
            $table->integer('team_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->string('avatar');
            $table->string('employee_id');
            $table->string('token');
            $table->timestamps();
            $table->foreign('team_id')
                ->references('id')
                ->on('team');
            $table->foreign('position_id')
                ->references('id')
                ->on('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
