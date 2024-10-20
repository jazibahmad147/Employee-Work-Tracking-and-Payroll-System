<?php

namespace Database\Seeders;

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
        // Other seeders can be called here
        $this->call([
            UsersTableSeeder::class,
        ]);
    }
}
