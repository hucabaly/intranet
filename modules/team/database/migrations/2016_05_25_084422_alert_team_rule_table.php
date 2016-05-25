<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTeamRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('team_position')) {
            Schema::table('team_position', function (Blueprint $table) {
                $table->dropForeign('team_position_team_id_foreign');
            });
            if (Schema::hasTable('team_rule')) {
                Schema::table('team_rule', function (Blueprint $table) {
                    $table->dropForeign('team_rule_team_position_id_foreign');
                });
            }
            Schema::dropIfExists('team_position');
        }
        
        //create column of team_rule
        if (!Schema::hasTable('team_rule')) {
            return;
        }
        if (Schema::hasColumn('team_rule', 'team_position_id')) {
            Schema::table('team_rule', function (Blueprint $table) {
                $table->dropColumn('team_position_id');
            });
        }
        if (!Schema::hasColumn('team_rule', 'team_id')) {
            Schema::table('team_rule', function (Blueprint $table) {
                $table->integer('team_id')->unsigned()->before('rule');
                $table->foreign('team_id')
                    ->references('id')
                    ->on('team');
            });
        }
        if (!Schema::hasColumn('team_rule', 'position_id')) {
            Schema::table('team_rule', function (Blueprint $table) {
                $table->integer('position_id')->unsigned()->after('team_id');
                $table->foreign('position_id')
                    ->references('id')
                    ->on('position');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
