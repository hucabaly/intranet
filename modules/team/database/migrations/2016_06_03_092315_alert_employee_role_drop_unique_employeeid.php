<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertEmployeeRoleDropUniqueEmployeeid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('employee_roles')) {
            return;
        }
        if (Schema::hasColumn('employee_roles', 'employee_id')) {
            Schema::table('employee_roles', function (Blueprint $table) {
                $table->dropForeign('employee_roles_employee_id_foreign');
                $table->dropUnique('employee_roles_employee_id_unique');
                $table->primary(['employee_id', 'role_id']);
                $table->foreign('employee_id')
                ->references('id')
                ->on('employees');
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
