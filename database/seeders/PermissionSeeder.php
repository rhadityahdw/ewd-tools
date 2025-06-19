<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (fixed list, tidak ada CRUD)
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role permissions
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Template permissions
            'view templates',
            'create templates',
            'edit templates',
            'delete templates',
            
            // Aspect permissions
            'view aspects',
            'create aspects',
            'edit aspects',
            'delete aspects',
            
            // Report permissions
            'view reports',
            'create reports',
            'edit reports',
            'delete reports',
            
            // Borrower permissions
            'view borrowers',
            'create borrowers',
            'edit borrowers',
            'delete borrowers',
            
            // Period permissions
            'view periods',
            'create periods',
            'edit periods',
            'delete periods',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create default roles
        $adminRole = Role::findByName('Super Admin');
        $adminRole->givePermissionTo(Permission::all());

        // $userRole = Role::create(['name' => 'user']);
        // $userRole->givePermissionTo([
        //     'view templates',
        //     'view aspects',
        //     'view reports',
        //     'create reports',
        //     'view borrowers',
        // ]);
        
        // $managerRole = Role::create(['name' => 'manager']);
        // $managerRole->givePermissionTo([
        //     'view users',
        //     'view templates',
        //     'create templates',
        //     'edit templates',
        //     'view aspects',
        //     'create aspects',
        //     'edit aspects',
        //     'view reports',
        //     'create reports',
        //     'edit reports',
        //     'view borrowers',
        //     'create borrowers',
        //     'edit borrowers',
        //     'view periods',
        //     'create periods',
        //     'edit periods',
        // ]);
    }
}