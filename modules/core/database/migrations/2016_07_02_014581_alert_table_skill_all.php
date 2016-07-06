<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTableSkillAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('proj_experiences')) {
            if (
                Schema::hasColumn('proj_experiences', 'start_at') && 
                Schema::hasColumn('proj_experiences', 'end_at')
            ) {
                Schema::table('proj_experiences', function (Blueprint $table) {
                    $table->date('start_at')->change();
                    $table->date('end_at')->change();
                });
            }
        }
        
        if (Schema::hasTable('work_experiences')) {
            if (
                Schema::hasColumn('work_experiences', 'start_at') && 
                Schema::hasColumn('work_experiences', 'end_at')
            ) {
                Schema::table('work_experiences', function (Blueprint $table) {
                    $table->date('start_at')->nullable()->change();
                    $table->date('end_at')->nullable()->change();
                });
            }
        }
        
        if (Schema::hasTable('employee_cetificates')) {
            if (
                Schema::hasColumn('employee_cetificates', 'start_at') && 
                Schema::hasColumn('employee_cetificates', 'end_at')
            ) {
                Schema::table('employee_cetificates', function (Blueprint $table) {
                    $table->date('start_at')->nullable()->change();
                    $table->date('end_at')->nullable()->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
