<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing admin if exists (optional, for re-seeding)
        User::where('email', 'amalissellay01@gmail.com')->delete();

        // Create the single admin user
        User::create([
            'name' => 'amal',
            'email' => 'amalissellay01@gmail.com',
            'password' => Hash::make('Aissell@1234+'),  // CHANGE THIS!
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: amalissellay01@gmail.com');
    }
}
