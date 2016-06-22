<?php
namespace Rikkei\Team\Seeds;

use Illuminate\Database\Seeder;
use Rikkei\Team\Model\Roles;

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
                'role' => 'Team Leader',
                'sort_order' => '1',
                'special_flg' => Roles::FLAG_POSITION,
            ],
            [
                'role' => 'Sub-Leader',
                'sort_order' => '2',
                'special_flg' => Roles::FLAG_POSITION,
            ],
            [
                'role' => 'Member',
                'sort_order' => '3',
                'special_flg' => Roles::FLAG_POSITION,
            ]
        ];
        foreach ($dataDemo as $data) {
            $rolePosition = Roles::where('role', $data['role'])->get();
            if (count($rolePosition)) {
                continue;
            }
            try {
                Roles::create($data);
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }
}
