<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'id' => 1,
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('password'),
            'gender_id' => 1,
            'role_id' => 4,
            'status_id' => 1,
            'company_id' => 1,
        ]);

        User::firstOrCreate([
            'first_name' => 'bishal',
            'last_name' => 'super admin',
            'email' => 'bishalsuperadmin@infodigita.com',
            'password' => bcrypt('password'),
            'gender_id' => 1,
            'role_id' => 4,
            'status_id' => 1,
            'parent_admin_id' => 1,
            'company_id' => 1,
        ]);
        User::firstOrCreate([
            'first_name' => 'Play Group',
            'last_name' => 'Leader',
            'email' => 'leader@mail.com',
            'password' => bcrypt('password'),
            'gender_id' => 1,
            'role_id' => 2,
            'status_id' => 1,
            'parent_admin_id' => 1,
            'company_id' => 1,
        ]);
    }
}
