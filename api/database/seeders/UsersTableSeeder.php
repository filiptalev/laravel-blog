<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Src\Domain\Models\User;
use App\Src\Domain\Services\Enums\UserRole;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('123123123'),
            'role' => UserRole::ADMINISTRATOR,
        ]);

        \DB::table('users')->insert([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('123123123'),
            'role' => UserRole::USER,
        ]);
    }
}
