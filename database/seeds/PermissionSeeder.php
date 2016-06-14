<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Rikkei\Core\Seeds\MenusSeeder::class);
        $this->call(Rikkei\Core\Seeds\MenuItemsSeeder::class);
        $this->call(Rikkei\Team\Seeds\ActionSeeder::class);
    }
}
