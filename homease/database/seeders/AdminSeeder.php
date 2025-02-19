<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::updateOrCreate(
            [
                'role' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'birthdate' => '2000-01-01',
                'gender' => '',
                'email' => 'admin@homease.com',
                'email_verified_at' => now(),
                'phone' => '',
                'street' => '',
                'barangay' => '',
                'city' => '',
                'zip_code' => '',
                'password' => Hash::make('homeaseadmin123'),
            ]
        );
    }
}
