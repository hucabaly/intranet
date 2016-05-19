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
            $table->integer('team_position_id')->unsigned();
            $table->string('rule');
            $table->enum('scope', \Rikkei\Team\Model\TeamRule::getScopes())
                ->default(\Rikkei\Team\Model\TeamRule::getScopeDefault());
            $table->unique(['team_position_id', 'rule']);
            $table->foreign('team_position_id')
                ->references('id')
                ->on('team_position');
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
