<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'rio'],
            [
                'name' => 'Rio Admin',
                'email' => 'rio@admin.com',
                'password' => Hash::make('qwertyui'),
                'role' => 'admin',
            ]
        );
    }
}
