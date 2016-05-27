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
        $this->call(Rikkei\Team\Seeds\PositionSeeder::class);
        $this->call(Rikkei\Sales\Seeds\CssQuestionSeeder::class);
        $this->call(Rikkei\Sales\Seeds\CssCategorySeeder::class);
    }
}
