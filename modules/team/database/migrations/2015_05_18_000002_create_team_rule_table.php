<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_rule', function (Blueprint $table) {
            $table->integer('team_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->string('rule');
            $table->enum('scope', \Rikkei\Team\Model\TeamRule::getScopes())
                ->default(\Rikkei\Team\Model\TeamRule::getScopeDefault());
            $table->unique(['team_id', 'position_id', 'rule']);
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
        Schema::drop('team_rule');
    }
}
