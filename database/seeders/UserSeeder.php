<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // updateOrCreate super admin role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
        ], [
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'admin',
        ], [
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'operator',
        ], [
            'guard_name' => 'web',
        ]);

        User::updateOrCreate([
            'email' => 'superadmin@siris.uhb.ac.id',
        ], [
            'name' => 'Super Admin',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ])->assignRole($superAdminRole);
        User::factory(5)->create();
    }
}
