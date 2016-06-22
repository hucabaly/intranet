<?php

use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Rikkei\Recruitment\Seeds\RecruitmentCampaignsSeeder::class);
        $this->call(Rikkei\Recruitment\Seeds\RecruitmentAppliesSeeder::class);
    }
}
