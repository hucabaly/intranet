<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RenameTableCetificate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cetificates') &&
            ! Schema::hasTable('certificates')) {
            Schema::rename('cetificates', 'certificates');
        }
        
        if (Schema::hasTable('employee_cetificates') &&
            ! Schema::hasTable('employee_certificates')) {
            Schema::rename('employee_cetificates', 'employee_certificates');
        }
        if (Schema::hasColumn('employee_certificates', 'cetificate_id')) {
            Schema::table('employee_certificates', function (Blueprint $table) {
                $table->dropForeign('employee_cetificates_cetificate_id_foreign');
                $table->renameColumn('cetificate_id', 'certificate_id');
                $table->foreign('certificate_id')
                    ->references('id')
                    ->on('certificates');
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
    }
}
