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
        // Check if admin already exists
        $adminEmail = 'admin@dalimacare.id';
        $adminPassword = 'password123';

        $user = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'username' => 'Administrator',
                'password' => Hash::make($adminPassword),
                'role'     => 'admin',
            ]
        );

        $this->command->info('---------------------------------------');
        $this->command->info('   Admin Account Updated/Created Successfully');
        $this->command->info('---------------------------------------');
        $this->command->info("   Email    : $adminEmail");
        $this->command->info("   Password : $adminPassword");
        $this->command->info("   Role     : admin");
        $this->command->info('---------------------------------------');
    }
}
