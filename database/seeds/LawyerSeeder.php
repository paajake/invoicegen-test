<?php

use Illuminate\Database\Seeder;

class LawyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Lawyer::class, 15)->create();
    }
}
