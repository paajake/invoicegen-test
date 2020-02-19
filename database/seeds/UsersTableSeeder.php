<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => "Attaa Adwoa",
            'email' => "admin@invoicegen.test",
            'password' => Hash::make('admin1234'),
        ]);

        factory(App\User::class, 15)->create();
    }
}
