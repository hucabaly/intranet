<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employees')) {
            return;
        }
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('birthday')->nullable();
            $table->string('nickname');
            $table->string('email')->unique();
            $table->integer('employee_card_id')->nullable();
            $table->string('employee_code')->nullable();
            $table->date('join_date')->nullable();
            $table->date('leave_date')->nullable();
            $table->string('persional_email')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->boolean('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('home_town')->nullable();
            $table->string('id_card_number')->nullable();
            $table->string('id_card_place')->nullable();
            $table->date('id_card_date')->nullable();
            $table->integer('recruiment_apply_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
