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
        if (DB::table('users')->exists()) {
            $this->command->info('Users table is not empty, skipping seeding.');

            return;
        }

        // updateOrCreate super admin role
        $superAdminRole = Role::updateOrCreate([
            'name' => 'super_admin',
        ], [
            'guard_name' => 'web',
        ]);

        User::updateOrCreate([
            'email' => 'superadmin@limas.uhb.ac.id',
        ], [
            'name' => 'Super Admin',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ])->assignRole($superAdminRole);
        User::factory(50)->create();
    }
}
