<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@ewd.test',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
            'division_id' => null
        ]);
        $admin->assignRole('Super Admin');

        // Create RM user
        $rm = User::create([
            'name' => 'rm',
            'email' => 'rm@ewd.test',
            'password' => Hash::make('rm12345'),
            'role_id' => 2,
            'division_id' => 1
        ]);
        $rm->assignRole('Relationship Manager');

        // Create ERO user
        $ero = User::create([
            'name' => 'ero',
            'email' => 'ero@ewd.test',
            'password' => Hash::make('ero12345'),
            'role_id' => 3,
            'division_id' => 2
        ]);
        $ero->assignRole('Executive Risk Officer');
    }
}
