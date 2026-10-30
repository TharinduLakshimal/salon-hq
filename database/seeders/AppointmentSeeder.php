<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Seed unisex salon appointments across Today, Tomorrow, Past, and Upcoming dates.
     */
    public function run(): void
    {
        if (Staff::query()->count() === 0) {
            $this->call([
                UserSeeder::class,
                StaffSeeder::class,
            ]);
        }

        $staffByEmail = Staff::with('user')
            ->get()
            ->keyBy(fn (Staff $staff): string => (string) $staff->user?->email);

        $categoriesByName = Category::query()->get()->keyBy('name');

        $clientsByEmail = User::query()
            ->where('role', 'client')
            ->get()
            ->keyBy('email');

        $yesterday = now()->subDay()->toDateString();
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        $upcomingWeekend = now()->addDays(3)->toDateString();

        $appointments = [
            // --- TODAY'S APPOINTMENTS (9 Bookings across all statuses, departments & stylists) ---
            [
                'booking_code' => 'BK-2026-0101',
                'client_email' => 'client@salonhq.com',
                'client_name' => 'Nethmi Perera',
                'client_phone' => '077 889 1122',
                'client_gender' => 'female',
                'staff_email' => 'kavindi@salonhq.com',
                'category_name' => 'Bridal & Makeup',
                'booking_type' => 'online_booking',
                'service_name' => 'Bridal Hair & HD Airbrush Makeup',
                'duration_minutes' => 210,
                'appointment_date' => $today,
                'start_time' => '09:30:00',
                'end_time' => '13:00:00',
                'price' => 18500.00,
                'status' => 'in_progress',
                'notes' => 'Traditional Kandyan jasmine hair styling requested.',
            ],
            [
                'booking_code' => 'BK-2026-0102',
                'client_email' => 'dilshan@example.com',
                'client_name' => 'Dilshan Silva',
                'client_phone' => '071 334 5566',
                'client_gender' => 'male',
                'staff_email' => 'roshan@salonhq.com',
                'category_name' => 'Barbers',
                'booking_type' => 'walk_in',
                'service_name' => 'Executive Skin Fade & Beard Styling',
                'duration_minutes' => 45,
                'appointment_date' => $today,
                'start_time' => '10:15:00',
                'end_time' => '11:00:00',
                'price' => 3200.00,
                'status' => 'in_progress',
                'notes' => 'Hot towel shave finish.',
            ],
            [
                'booking_code' => 'BK-2026-0103',
                'client_email' => 'sachini.fdo@example.com',
                'client_name' => 'Sachini Fernando',
                'client_phone' => '076 992 1044',
                'client_gender' => 'female',
                'staff_email' => 'amaya@salonhq.com',
                'category_name' => 'Hair Stylists',
                'booking_type' => 'phone_desk',
                'service_name' => 'Brazilian Keratin Hair Treatment',
                'duration_minutes' => 165,
                'appointment_date' => $today,
                'start_time' => '11:00:00',
                'end_time' => '13:45:00',
                'price' => 14000.00,
                'status' => 'confirmed',
                'notes' => 'Sulfate-free aftercare kit inquiry.',
            ],
            [
                'booking_code' => 'BK-2026-0104',
                'client_email' => 'tharindu.j@example.com',
                'client_name' => 'Tharindu Jayasinghe',
                'client_phone' => '077 203 5511',
                'client_gender' => 'male',
                'staff_email' => 'nadeesha@salonhq.com',
                'category_name' => 'Estheticians & Nails',
                'booking_type' => 'online_booking',
                'service_name' => '7-Step Platinum HydraFacial',
                'duration_minutes' => 75,
                'appointment_date' => $today,
                'start_time' => '13:30:00',
                'end_time' => '14:45:00',
                'price' => 7500.00,
                'status' => 'confirmed',
                'notes' => 'Sensitive skin — mild enzyme peel only.',
            ],
            [
                'booking_code' => 'BK-2026-0105',
                'client_email' => 'hashini.w@example.com',
                'client_name' => 'Hashini Wijeratne',
                'client_phone' => '070 331 6780',
                'client_gender' => 'female',
                'staff_email' => 'tashya@salonhq.com',
                'category_name' => 'Estheticians & Nails',
                'booking_type' => 'online_booking',
                'service_name' => 'BIAB Gel Nail Extensions & Chrome Art',
                'duration_minutes' => 90,
                'appointment_date' => $today,
                'start_time' => '15:00:00',
                'end_time' => '16:30:00',
                'price' => 5300.00,
                'status' => 'pending',
                'notes' => 'Almond shape pearl glaze.',
            ],
            [
                'booking_code' => 'BK-2026-0106',
                'client_email' => 'malki.d@example.com',
                'client_name' => 'Malki Dissanayake',
                'client_phone' => '071 609 3321',
                'client_gender' => 'female',
                'staff_email' => 'shenal@salonhq.com',
                'category_name' => 'Hair Stylists',
                'booking_type' => 'walk_in',
                'service_name' => 'Butterfly Layer Cut & Blowout',
                'duration_minutes' => 60,
                'appointment_date' => $today,
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'price' => 4500.00,
                'status' => 'completed',
                'notes' => 'Paid via card at POS.',
            ],
            [
                'booking_code' => 'BK-2026-0107',
                'client_email' => 'nuwan.r@example.com',
                'client_name' => 'Nuwan Rajapaksa',
                'client_phone' => '077 645 9012',
                'client_gender' => 'male',
                'staff_email' => 'roshan@salonhq.com',
                'category_name' => 'Barbers',
                'booking_type' => 'walk_in',
                'service_name' => 'Executive Skin Fade & Beard Styling',
                'duration_minutes' => 45,
                'appointment_date' => $today,
                'start_time' => '12:00:00',
                'end_time' => '12:45:00',
                'price' => 3200.00,
                'status' => 'confirmed',
                'notes' => 'Mid taper fade with beard oil conditioning.',
            ],
            [
                'booking_code' => 'BK-2026-0108',
                'client_email' => 'chathuri.s@example.com',
                'client_name' => 'Chathuri Silva',
                'client_phone' => '076 410 7782',
                'client_gender' => 'female',
                'staff_email' => 'kavindi@salonhq.com',
                'category_name' => 'Bridal & Makeup',
                'booking_type' => 'online_booking',
                'service_name' => 'Party Glam Makeup & Soft Curls',
                'duration_minutes' => 90,
                'appointment_date' => $today,
                'start_time' => '16:30:00',
                'end_time' => '18:00:00',
                'price' => 12500.00,
                'status' => 'cancelled',
                'notes' => 'Client requested reschedule to next weekend.',
            ],
            [
                'booking_code' => 'BK-2026-0109',
                'client_email' => null, // Walk-in guest without a registered User account
                'client_name' => 'Senuri Gamage',
                'client_phone' => '077 450 9821',
                'client_gender' => 'female',
                'staff_email' => 'shenal@salonhq.com',
                'category_name' => 'Hair Stylists',
                'booking_type' => 'walk_in',
                'service_name' => 'Butterfly Layer Cut & Blowout',
                'duration_minutes' => 60,
                'appointment_date' => $today,
                'start_time' => '14:00:00',
                'end_time' => '15:00:00',
                'price' => 4500.00,
                'status' => 'pending',
                'notes' => 'Walk-in guest requesting curtain bangs and volume blowout.',
            ],

            // --- TOMORROW'S APPOINTMENTS (4 Bookings) ---
            [
                'booking_code' => 'BK-2026-0110',
                'client_email' => 'kasun.bandara@example.com',
                'client_name' => 'Kasun Bandara',
                'client_phone' => '077 889 4510',
                'client_gender' => 'male',
                'staff_email' => 'roshan@salonhq.com',
                'category_name' => 'Barbers',
                'booking_type' => 'phone_desk',
                'service_name' => 'Groom Luxury Hair & Detox Facial Package',
                'duration_minutes' => 120,
                'appointment_date' => $tomorrow,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'price' => 14500.00,
                'status' => 'confirmed',
                'notes' => 'Wedding trial consultation.',
            ],
            [
                'booking_code' => 'BK-2026-0111',
                'client_email' => 'navodya.j@example.com',
                'client_name' => 'Navodya Jayasuriya',
                'client_phone' => '071 920 3418',
                'client_gender' => 'female',
                'staff_email' => 'kavindi@salonhq.com',
                'category_name' => 'Bridal & Makeup',
                'booking_type' => 'online_booking',
                'service_name' => 'Party Glam Makeup & Soft Curls',
                'duration_minutes' => 90,
                'appointment_date' => $tomorrow,
                'start_time' => '11:30:00',
                'end_time' => '13:00:00',
                'price' => 12500.00,
                'status' => 'confirmed',
                'notes' => 'Evening reception saree draping included.',
            ],
            [
                'booking_code' => 'BK-2026-0112',
                'client_email' => 'shehan.g@example.com',
                'client_name' => 'Shehan Gunawardena',
                'client_phone' => '076 512 8840',
                'client_gender' => 'male',
                'staff_email' => 'nadeesha@salonhq.com',
                'category_name' => 'Estheticians & Nails',
                'booking_type' => 'online_booking',
                'service_name' => '7-Step Platinum HydraFacial',
                'duration_minutes' => 75,
                'appointment_date' => $tomorrow,
                'start_time' => '14:00:00',
                'end_time' => '15:15:00',
                'price' => 7500.00,
                'status' => 'pending',
                'notes' => 'First-time facial session.',
            ],
            [
                'booking_code' => 'BK-2026-0113',
                'client_email' => 'oshadi.s@example.com',
                'client_name' => 'Oshadi Samarasinghe',
                'client_phone' => '077 319 6645',
                'client_gender' => 'female',
                'staff_email' => 'tashya@salonhq.com',
                'category_name' => 'Estheticians & Nails',
                'booking_type' => 'phone_desk',
                'service_name' => 'BIAB Gel Nail Extensions & Chrome Art',
                'duration_minutes' => 90,
                'appointment_date' => $tomorrow,
                'start_time' => '15:30:00',
                'end_time' => '17:00:00',
                'price' => 5300.00,
                'status' => 'confirmed',
                'notes' => 'French tip rose chrome finish.',
            ],

            // --- YESTERDAY & UPCOMING CUSTOM DATES (3 Bookings for Custom Date filter testing) ---
            [
                'booking_code' => 'BK-2026-0114',
                'client_email' => 'ravindu.e@example.com',
                'client_name' => 'Ravindu Ekanayake',
                'client_phone' => '075 480 2290',
                'client_gender' => 'male',
                'staff_email' => 'roshan@salonhq.com',
                'category_name' => 'Barbers',
                'booking_type' => 'walk_in',
                'service_name' => 'Executive Skin Fade & Beard Styling',
                'duration_minutes' => 45,
                'appointment_date' => $yesterday,
                'start_time' => '16:00:00',
                'end_time' => '16:45:00',
                'price' => 3200.00,
                'status' => 'completed',
                'notes' => 'Completed walk-in session.',
            ],
            [
                'booking_code' => 'BK-2026-0115',
                'client_email' => 'dinithi.a@example.com',
                'client_name' => 'Dinithi Abeysekara',
                'client_phone' => '076 774 1920',
                'client_gender' => 'female',
                'staff_email' => 'amaya@salonhq.com',
                'category_name' => 'Hair Stylists',
                'booking_type' => 'online_booking',
                'service_name' => 'Brazilian Keratin Hair Treatment',
                'duration_minutes' => 165,
                'appointment_date' => $upcomingWeekend,
                'start_time' => '10:00:00',
                'end_time' => '12:45:00',
                'price' => 14000.00,
                'status' => 'confirmed',
                'notes' => 'Long waist-length hair — reserve extra smoothing serum.',
            ],
            [
                'booking_code' => 'BK-2026-0116',
                'client_email' => null, // Phone-desk guest booking without User account
                'client_name' => 'Kusal Perera',
                'client_phone' => '071 804 5190',
                'client_gender' => 'male',
                'staff_email' => 'shenal@salonhq.com',
                'category_name' => 'Hair Stylists',
                'booking_type' => 'phone_desk',
                'service_name' => 'Butterfly Layer Cut & Blowout',
                'duration_minutes' => 60,
                'appointment_date' => $upcomingWeekend,
                'start_time' => '13:00:00',
                'end_time' => '14:00:00',
                'price' => 4500.00,
                'status' => 'pending',
                'notes' => 'Men\'s medium-length textured scissor restyle.',
            ],
        ];

        $fallbackStaff = $staffByEmail->first();

        foreach ($appointments as $item) {
            $staff = $staffByEmail->get($item['staff_email']) ?? $fallbackStaff;
            if (! $staff) {
                continue;
            }

            $client = $item['client_email']
                ? $clientsByEmail->get($item['client_email'])
                : null;

            $category = $categoriesByName->get($item['category_name']);

            Appointment::updateOrCreate(
                ['booking_code' => $item['booking_code']],
                [
                    'client_id' => $client?->id,
                    'staff_id' => $staff->id,
                    'category_id' => $category?->id ?? $staff->category_id,
                    'client_name' => $item['client_name'],
                    'client_phone' => $item['client_phone'],
                    'client_gender' => $item['client_gender'],
                    'booking_type' => $item['booking_type'],
                    'service_name' => $item['service_name'],
                    'duration_minutes' => $item['duration_minutes'],
                    'appointment_date' => $item['appointment_date'],
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'price' => $item['price'],
                    'status' => $item['status'],
                    'notes' => $item['notes'],
                ]
            );
        }
    }
}
