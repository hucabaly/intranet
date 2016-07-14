<?php
namespace Rikkei\Sales\Seeds;

use Illuminate\Database\Seeder;
use DB;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataDemo = [
            [
                'name' => 'OSDC',
            ],
            [
                'name' => 'Project base',
            ]
        ];
        foreach ($dataDemo as $data) {
            if (! DB::table('css_project_type')->select('id')->where('name', $data['name'])->get()) {
                DB::table('css_project_type')->insert($data);
            }
        }
    }
}
