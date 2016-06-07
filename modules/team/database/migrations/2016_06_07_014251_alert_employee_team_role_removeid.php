<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertEmployeeTeamRoleRemoveid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employee_roles')) {
            if (Schema::hasColumn('employee_roles', 'id')) {
                Schema::table('employee_roles', function (Blueprint $table) {
                    $table->dropColumn('id');
                });
            }
        }
        if (Schema::hasTable('team_members')) {
            if (Schema::hasColumn('team_members', 'id')) {
                Schema::table('team_members', function (Blueprint $table) {
                    $table->dropColumn('id');
                });
            }
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
