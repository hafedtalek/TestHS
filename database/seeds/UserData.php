<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
class UserData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create(['name' => 'test1', 'email' => 'tets1@mail.com', 'password' => Hash::make("123456")]);
        User::create(['name' => 'test2', 'email' => 'tets2@mail.com', 'password' => Hash::make("123456")]);
        User::create(['name' => 'test3', 'email' => 'tets3@mail.com', 'password' => Hash::make("123456")]);
        User::create(['name' => 'test4', 'email' => 'tets4@mail.com', 'password' => Hash::make("123456")]);
        User::create(['name' => 'test5', 'email' => 'tets5@mail.com', 'password' => Hash::make("123456")]);
    }
}
