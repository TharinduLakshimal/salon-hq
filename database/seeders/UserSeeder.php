<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed default users (Owners, Workers, and Clients) for easy testing.
     */
    public function run(): void
    {
        $defaultUsers = [
            // 1. Salon Owners / Admins
            [
                'name' => 'Salon Owner',
                'email' => 'owner@salonhq.com',
                'password' => 'password',
                'phone_number' => '077 100 2000',
                'gender' => 'male',
                'role' => 'owner',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test Admin',
                'email' => 'test@example.com',
                'password' => 'password',
                'phone_number' => '077 123 4567',
                'gender' => 'male',
                'role' => 'owner',
                'email_verified_at' => now(),
            ],

            // 2. Salon Workers / Stylists
            [
                'name' => 'Kavindi Senanayake',
                'email' => 'kavindi@salonhq.com',
                'password' => 'password',
                'phone_number' => '077 412 8930',
                'gender' => 'female',
                'role' => 'worker',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Roshan Mendis',
                'email' => 'roshan@salonhq.com',
                'password' => 'password',
                'phone_number' => '071 845 2219',
                'gender' => 'male',
                'role' => 'worker',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Amaya Jayawardena',
                'email' => 'amaya@salonhq.com',
                'password' => 'password',
                'phone_number' => '076 992 1044',
                'gender' => 'female',
                'role' => 'worker',
                'email_verified_at' => now(),
            ],

            // 3. Salon Clients / Customers
            [
                'name' => 'Nethmi Perera',
                'email' => 'client@salonhq.com',
                'password' => 'password',
                'phone_number' => '077 889 1122',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dilshan Silva',
                'email' => 'dilshan@example.com',
                'password' => 'password',
                'phone_number' => '071 334 5566',
                'gender' => 'male',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($defaultUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

