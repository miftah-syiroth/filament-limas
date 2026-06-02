<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
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

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ], [
            'guard_name' => 'web',
        ]);

        $operatorRole = Role::firstOrCreate([
            'name' => 'operator',
        ], [
            'guard_name' => 'web',
        ]);

        User::updateOrCreate([
            'email' => 'superadmin@siris.uhb.ac.id',
        ], [
            'name' => 'Super Admin',
            'email_verified_at' => now(),
            'password' => bcrypt('MvY%BVbAq#XTQFHd9aH1'),
            'remember_token' => Str::random(10),
        ])->assignRole($superAdminRole);
        
        // create admin user
        User::updateOrCreate([
            'email' => 'admin@siris.uhb.ac.id',
        ], [
            'name' => 'Admin',
            'email_verified_at' => now(),
            'password' => bcrypt('ASDqwe123!@#'),
            'remember_token' => Str::random(10),
        ])->assignRole($adminRole);

        // create operator user
        User::updateOrCreate([
            'email' => 'operator@siris.uhb.ac.id',
        ], [
            'name' => 'Operator',
            'email_verified_at' => now(),
            'password' => bcrypt('ASDqwe123!@#'),
            'remember_token' => Str::random(10),
        ])->assignRole($operatorRole);
    }
}
