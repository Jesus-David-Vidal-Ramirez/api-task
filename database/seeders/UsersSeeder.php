<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Jesus David',
            'email' => 'Ramirez_vidal@outlook.com',
            'role_id' => '1',
            'password' => '123456789',
        ]);

        User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'role_id' => '2',
            'password' => '123456789',
        ]);
    }
}
