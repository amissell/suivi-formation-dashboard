<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'must.negoce01@gmail.com'],
            [
                'name' => 'Mustapha',
                'password' => Hash::make('mustnegoce2025'),
            ]
        );
    }
}
