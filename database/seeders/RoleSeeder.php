<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'admin',
            'playgroup lead',
            'playgroup assistant',
            "super_admin"
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
               'name' => $role
            ]);
        }
    }
}
