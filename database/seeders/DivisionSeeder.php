<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['code' => 'KWS', 'name' => 'Kantor Wilayah Satu'],
            ['code' => 'KWD', 'name' => 'Kantor Wilayah Dua'],
            ['code' => 'KWT', 'name' => 'Kantor Wilayah Tiga'],
        ];

        DB::table('divisions')->insert($divisions);
    }
}
