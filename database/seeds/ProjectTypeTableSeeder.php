<?php

use Illuminate\Database\Seeder;

class ProjectTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_type')
	        ->insert([
	            'name' => 'OSDC',
	        ]);
		DB::table('project_type')
	        ->insert([
	            'name' => 'Project base',
	        ]);
    }
}
