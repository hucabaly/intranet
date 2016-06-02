<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->timestamps();
            $table->unique(['employee_id', 'team_id']);
            $table->foreign('team_id')
                ->references('id')
                ->on('team');
            $table->foreign('position_id')
                ->references('id')
                ->on('position');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('team_members');
    }
}
