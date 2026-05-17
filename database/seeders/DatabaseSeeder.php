<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::updateOrCreate(
            ['email' => 'admin@abstractflow.com'],
            [
                'name' => 'Master Admin',
                'password' => Hash::make('AdminSecure2026!'), // Fixed password
                'role' => 'admin',
                'status' => true
            ]
        );
    }
}