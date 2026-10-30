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

            // 3. Salon Clients / Customers (16 Unisex Clients: 8 Female, 8 Male)
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
            [
                'name' => 'Sachini Fernando',
                'email' => 'sachini.fdo@example.com',
                'password' => 'password',
                'phone_number' => '076 992 1044',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Tharindu Jayasinghe',
                'email' => 'tharindu.j@example.com',
                'password' => 'password',
                'phone_number' => '077 203 5511',
                'gender' => 'male',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Hashini Wijeratne',
                'email' => 'hashini.w@example.com',
                'password' => 'password',
                'phone_number' => '070 331 6780',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kasun Bandara',
                'email' => 'kasun.bandara@example.com',
                'password' => 'password',
                'phone_number' => '077 889 4510',
                'gender' => 'male',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Malki Dissanayake',
                'email' => 'malki.d@example.com',
                'password' => 'password',
                'phone_number' => '071 609 3321',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Shehan Gunawardena',
                'email' => 'shehan.g@example.com',
                'password' => 'password',
                'phone_number' => '076 512 8840',
                'gender' => 'male',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Chathuri Silva',
                'email' => 'chathuri.s@example.com',
                'password' => 'password',
                'phone_number' => '076 410 7782',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Nuwan Rajapaksa',
                'email' => 'nuwan.r@example.com',
                'password' => 'password',
                'phone_number' => '077 645 9012',
                'gender' => 'male',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Navodya Jayasuriya',
                'email' => 'navodya.j@example.com',
                'password' => 'password',
                'phone_number' => '071 920 3418',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ravindu Ekanayake',
                'email' => 'ravindu.e@example.com',
                'password' => 'password',
                'phone_number' => '075 480 2290',
                'gender' => 'male',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Oshadi Samarasinghe',
                'email' => 'oshadi.s@example.com',
                'password' => 'password',
                'phone_number' => '077 319 6645',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lahiru Weerasinghe',
                'email' => 'lahiru.w@example.com',
                'password' => 'password',
                'phone_number' => '071 552 8073',
                'gender' => 'male',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dinithi Abeysekara',
                'email' => 'dinithi.a@example.com',
                'password' => 'password',
                'phone_number' => '076 774 1920',
                'gender' => 'female',
                'role' => 'client',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Isuru Wickramaratne',
                'email' => 'isuru.w@example.com',
                'password' => 'password',
                'phone_number' => '077 908 4315',
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
