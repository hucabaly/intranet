<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create column avatar
        if(!Schema::hasTable('users')) {
            return;
        }
        if(Schema::hasColumn('users', 'avatar')) {
            return;
        }
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar');
        });
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
