<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use DB;

class PositionSeeder extends Seeder
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
                'name' => 'Team Leader',
                'level' => '1'
            ],
            [
                'name' => 'Sub-Leader',
                'level' => '2'
            ],
            [
                'name' => 'Member',
                'level' => '3'
            ]
        ];
        foreach ($dataDemo as $data) {
            if (! DB::table('position')->select('id')->where('name', $data['name'])->get()) {
                DB::table('position')->insert($data);
            }
        }
    }
}
