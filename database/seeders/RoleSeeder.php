<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Relationship Manager',
            'Kepala Departemen RM',
            'Business Risk Analyst',
            'Senior Risk Analyst',
            'Executive Risk Officer'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
