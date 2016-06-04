<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('role_rule')) {
            return;
        }
        Schema::create('role_rule', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->string('rule');
            $table->enum('scope', \Rikkei\Team\Model\TeamRule::getScopes())
                ->default(\Rikkei\Team\Model\TeamRule::getScopeDefault());
            $table->unique(['role_id', 'rule']);
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
        Schema::drop('role_rule');
    }
}
