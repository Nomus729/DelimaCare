<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tidak duplikat
        if (!User::where('email', 'admin@delimacare.id')->exists()) {
            User::create([
                'username' => 'Admin DelimaCare',
                'email'    => 'admin@delimacare.id',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]);
            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
