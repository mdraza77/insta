<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ek fix user for development/testing
        User::factory()->create([
            'name' => 'Md Raza',
            'username' => '__mdraza',
            'email' => 'mdraza8297@gmail.com',
            'password' => Hash::make('Success2026$'), // Password: Success2026$
        ]);

        // 2. 10 random dummy users for suggestions
        User::factory(10)->create();
    }
}
