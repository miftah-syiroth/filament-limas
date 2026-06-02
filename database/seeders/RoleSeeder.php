<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get all permissions and attach to admin
        $permissions = Permission::all();
        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions($permissions);
    }
}
