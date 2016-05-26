<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Rikkei\Team\Model\User;

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
        if (! Schema::hasTable('users')) {
            return;
        }
        
        if (! Schema::hasColumn('users', 'team_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('team_id')->unsigned();
            });
            $data = User::createTeamPositionDefault();
            User::where('team_id', 0)
                ->update([
                        'team_id' => $data['team']->id
                    ]);
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('team_id')
                    ->references('id')
                    ->on('team');
            });
        }
        if (! Schema::hasColumn('users', 'position_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('position_id')->unsigned();
            });
            $data = User::createTeamPositionDefault();
            User::where('position_id', 0)
                ->update([
                        'position_id' => $data['position']->id
                    ]);
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('position_id')
                    ->references('id')
                    ->on('position');
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
