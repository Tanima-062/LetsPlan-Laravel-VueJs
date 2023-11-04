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
        $this->call([
            MotherTongueSeeder::class,
            StatusSeeder::class,
            NationalitySeeder::class,
            RoleSeeder::class,
            CompanySeeder::class,
            CitySeeder::class,
            UserAdminSeeder::class,
            SessionSeeder::class,
        ]);
    }
}
