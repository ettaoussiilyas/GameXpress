<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'view categories']);
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // Create Roles
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $productManagerRole = Role::create(['name' => 'product_manager']);
        $userManagerRole = Role::create(['name' => 'user_manager']);

        // Assign all permissions to super-admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to admin
        $productManagerRole->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories'
        ]);

        // Assign limited permissions to moderator
        $userManagerRole->givePermissionTo([
            'view products', 'view categories', 'view users', 'create users', 'edit users', 'delete users'
        ]);
    }

}
