<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employee_roles')) {
            return;
        }
        Schema::create('employee_roles', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned()->unique();
            $table->integer('role_id')->unsigned();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employee_roles');
    }
}
