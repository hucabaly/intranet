<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTeamMembersColUserEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //replace column user_id by employee_id
        if (! Schema::hasTable('team_members')) {
            return;
        }
        if (Schema::hasColumn('team_members', 'user_id')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->dropForeign('team_members_user_id_foreign');
                $table->dropUnique('team_members_user_id_team_id_unique');
                $table->dropColumn('user_id');
            });
        }
        
        if (! Schema::hasColumn('team_members', 'employee_id')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->integer('employee_id')->unsigned();
                $table->foreign('employee_id')
                    ->references('id')
                    ->on('employees');
                $table->unique(['employee_id', 'team_id']);
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
