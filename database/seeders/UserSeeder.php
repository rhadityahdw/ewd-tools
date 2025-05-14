<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@ewd.test',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
                'division_id' => null
            ],
            [
                'name' => 'rm',
                'email' => 'rm@ewd.test',
                'password' => Hash::make('rm12345'),
                'role_id' => 2,
                'division_id' => 1
            ],
            [
                'name' => 'ero',
                'email' => 'ero@ewd.test',
                'password' => Hash::make('ero12345'),
                'role_id' => 3,
                'division_id' => 2
            ]
        ];

        DB::table('users')->insert($users);
    }
}
