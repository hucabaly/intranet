<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Rikkei\Team\Seeds\TeamSeeder::class);
        $this->call(Rikkei\Team\Seeds\PositionSeeder::class);
        $this->call(Rikkei\Sales\Seeds\CssCategorySeeder::class);
        $this->call(Rikkei\Sales\Seeds\CssQuestionSeeder::class);
        
        $this->call(Rikkei\Team\Seeds\ActionSeeder::class);
        $this->call(Rikkei\Core\Seeds\MenusSeeder::class);
        $this->call(Rikkei\Core\Seeds\MenuItemsSeeder::class);
        
        $this->call(Rikkei\Recruitment\Seeds\RecruitmentCampaignsSeeder::class);
        $this->call(Rikkei\Recruitment\Seeds\RecruitmentAppliesSeeder::class);
        
        $this->call(Rikkei\Team\Seeds\UserSeeder::class);
        $this->call(Rikkei\Team\Seeds\PermissionSeeder::class);
        
    }
}
