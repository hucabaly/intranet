<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmployeeCetificates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employee_cetificates')) {
            return;
        }
        Schema::create('employee_cetificates', function (Blueprint $table) {
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('cetificate_id');
            $table->smallInteger('level')->nullable();
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            
            $table->primary(['employee_id', 'cetificate_id']);
            $table->index('cetificate_id');
            $table->foreign('cetificate_id')
                ->references('id')
                ->on('cetificates');
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
        Schema::drop('employee_cetificates');
    }
}
