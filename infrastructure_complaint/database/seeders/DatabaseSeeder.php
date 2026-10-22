<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = [
            [
                'name' => "admin" , 
                'email' => "admin@example.com",
                'password' => Hash::make('password123'),
                'role' => "admin",
            ],
            [
                'name' => "staff" , 
                'email' => "staff@example.com",
                'password' => Hash::make('password123'),
                'role' => "staff",
            ],
        ];
        foreach ($user as $users) {
            User::create($users);
        }
    }
}
