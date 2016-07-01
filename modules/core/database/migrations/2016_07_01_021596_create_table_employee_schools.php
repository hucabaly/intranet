<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmployeeSchools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employee_schools')) {
            return;
        }
        Schema::create('employee_id', function (Blueprint $table) {
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('school_id');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('majors')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            
            $table->primary(['employee_id', 'school_id']);
            $table->index('school_id');
            $table->foreign('school_id')
                ->references('id')
                ->on('schools');
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
        Schema::drop('employee_schools');
    }
}
