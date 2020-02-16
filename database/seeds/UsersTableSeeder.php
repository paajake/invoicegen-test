<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Attaa Adwoa",
            'email' => "admin@invoicegen.test",
            'password' => Hash::make('admin1234'),
        ]);

        factory(App\User::class, 15)->create();
    }
}
