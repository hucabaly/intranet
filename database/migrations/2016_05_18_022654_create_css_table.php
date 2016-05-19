<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('css', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('project_type_id');
            $table->string('company_name');
            $table->string('customer_name');
            $table->string('project_name');
            $table->string('pm_name');
            $table->string('brse_name');
            $table->string('token');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::drop('css');
    }
}
