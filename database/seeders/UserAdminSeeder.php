<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Super Admin',
                'email' => 'super@solid.com',
                'password' => bcrypt('super@solid.com'),
            ]
        );

    }
}
