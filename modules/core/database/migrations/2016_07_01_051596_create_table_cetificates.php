<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCetificates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cetificates')) {
            return;
        }
        Schema::create('cetificates', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('type');
            $table->string('');
            
            
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('cetificate_id');
            $table->smallInteger('level')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
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
        Schema::drop('cetificates');
    }
}
