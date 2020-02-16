<?php

use Illuminate\Database\Seeder;
use App\Rank;
class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rank::create([
            'name' => "Associate",
            'rate' => 100,
        ]);

        Rank::create([
            'name' => "Junior Partner",
            'rate' => 200,
        ]);

        Rank::create([
            'name' => "Partner",
            'rate' => 500,
        ]);

        Rank::create([
            'name' => "Senior Partner",
            'rate' => 1000,
        ]);

        Rank::create([
            'name' => "Managing Partner",
            'rate' => 9000,
        ]);

        factory(App\Rank::class)->create();
    }
}
