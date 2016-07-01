<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmployeeSkills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employee_skills')) {
            return;
        }
        Schema::create('employee_skills', function (Blueprint $table) {
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('skill_id');
            $table->smallInteger('level')->nullable();
            $table->smallInteger('experience')->nullable();            
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            
            $table->primary(['employee_id', 'skill_id']);
            $table->index('skill_id');
            $table->foreign('skill_id')
                ->references('id')
                ->on('skills');
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
        Schema::drop('employee_skills');
    }
}
