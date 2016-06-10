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
                'name' => Menus::MENU_DEFAULT,
                'state' => '1'
            ]
        ];
        foreach ($dataDemo as $data) {
            if (count(Menus::where('name', $data['name'])->get())) {
                continue;
            }
            Menus::create($data);
        }
    }
}
