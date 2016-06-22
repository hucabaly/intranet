<?php
namespace Rikkei\Core\Seeds;

use Illuminate\Database\Seeder;
use Rikkei\Core\Model\Menus;

class MenusSeeder extends Seeder
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
                'name' => 'Rikkei Intranet',
                'state' => Menus::FLAG_MAIN
            ],
            [
                'name' => 'Setting',
                'state' => Menus::FLAG_SETTING
            ]
        ];
        foreach ($dataDemo as $data) {
            if (count(Menus::where('state', $data['state'])->get())) {
                continue;
            }
            $menu = new Menus();
            $menu->setData($data);
            $menu->save();
        }
    }
}
