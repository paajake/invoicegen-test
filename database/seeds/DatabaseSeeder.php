<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(TitleSeeder::class);
         $this->call(RankSeeder::class);
         $this->call(ClientSeeder::class);
         $this->call(LawyerSeeder::class);
    }
}
