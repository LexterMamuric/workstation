<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create default permissions
        Permission::create(['name' => 'list componentcategories']);
        Permission::create(['name' => 'view componentcategories']);
        Permission::create(['name' => 'create componentcategories']);
        Permission::create(['name' => 'update componentcategories']);
        Permission::create(['name' => 'delete componentcategories']);

        Permission::create(['name' => 'list componentmakes']);
        Permission::create(['name' => 'view componentmakes']);
        Permission::create(['name' => 'create componentmakes']);
        Permission::create(['name' => 'update componentmakes']);
        Permission::create(['name' => 'delete componentmakes']);

        Permission::create(['name' => 'list componentmodels']);
        Permission::create(['name' => 'view componentmodels']);
        Permission::create(['name' => 'create componentmodels']);
        Permission::create(['name' => 'update componentmodels']);
        Permission::create(['name' => 'delete componentmodels']);

        Permission::create(['name' => 'list componenttypes']);
        Permission::create(['name' => 'view componenttypes']);
        Permission::create(['name' => 'create componenttypes']);
        Permission::create(['name' => 'update componenttypes']);
        Permission::create(['name' => 'delete componenttypes']);

        // Create user role and assign existing permissions
        $currentPermissions = Permission::all();
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo($currentPermissions);

        // Create admin exclusive permissions
        Permission::create(['name' => 'list roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'delete roles']);

        Permission::create(['name' => 'list permissions']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'create permissions']);
        Permission::create(['name' => 'update permissions']);
        Permission::create(['name' => 'delete permissions']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        // Create admin role and assign all permissions
        $allPermissions = Permission::all();
        $adminRole = Role::create(['name' => 'super-admin']);
        $adminRole->givePermissionTo($allPermissions);

        $user = \App\Models\User::whereEmail('admin@admin.com')->first();

        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}
