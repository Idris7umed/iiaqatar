<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@iiaqatar.org',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Instructor user
        $instructor = User::create([
            'name' => 'Instructor User',
            'email' => 'instructor@iiaqatar.org',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $instructor->assignRole('instructor');

        // Member user
        $member = User::create([
            'name' => 'Member User',
            'email' => 'member@iiaqatar.org',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $member->assignRole('member');

        // Regular user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@iiaqatar.org',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $user->assignRole('user');
    }
}
