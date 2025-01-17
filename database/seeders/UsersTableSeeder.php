<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'id' => 1,
            'name' => 'Jazib Ahmad',
            'email' => 'jazib.ahmad147@hotmail.com',
            'password' => Hash::make('Pass123'),
        ]);
    }
}
