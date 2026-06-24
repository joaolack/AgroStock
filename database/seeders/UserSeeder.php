<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@agrostock.local'],
            [
                'name' => 'Usuario Demo',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
    }
}
