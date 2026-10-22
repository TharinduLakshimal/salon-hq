<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffWork;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Seed worker users, their staff profiles, and their portfolio works into the database.
     */
    public function run(): void
    {
        $workers = [
            [
                'user' => [
                    'name' => 'Kavindi Senanayake',
                    'email' => 'kavindi@salonhq.com',
                    'password' => 'password',
                    'phone_number' => '077 412 8930',
                    'gender' => 'female',
                    'role' => 'worker',
                    'email_verified_at' => now(),
                ],
                'profile' => [
                    'job_title' => 'Senior Bridal & Makeup Stylist',
                    'category' => 'Bridal & Makeup',
                    'serves_gender' => 'female',
                    'availability_status' => 'in_session',
                    'experience' => '7 Years',
                    'joined_date' => 'March 2021',
                    'rating' => 4.9,
                    'reviews_count' => 148,
                    'completed_works' => 620,
                    'today_bookings' => 4,
                    'monthly_revenue' => 'Rs. 485,000',
                    'repeat_clients' => '91%',
                    'avatar_gradient' => 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                    'bio' => 'Certified bridal makeup artist and hair designer specializing in Kandyan & Western bridal dressing, HD airbrush makeup, and luxury hair up-styles.',
                    'specialties' => ['Bridal Dressing', 'HD Airbrush Makeup', 'Hair Up-styling', 'Saree Draping', 'Party Glam'],
                    'schedule' => [
                        'Mon - Fri' => '09:00 AM - 06:00 PM',
                        'Saturday' => '08:00 AM - 07:00 PM',
                        'Sunday' => 'By Bridal Appointment',
                    ],
                ],
                'works' => [
                    [
                        'title' => 'Traditional Kandyan Bridal Look',
                        'category' => 'Bridal Package',
                        'client_name' => 'Nethmi Perera',
                        'performed_date' => 'Oct 20, 2026',
                        'duration' => '3h 30m',
                        'price' => 'Rs. 65,000',
                        'rating' => 5.0,
                        'accent_color' => '#8b5cf6',
                        'description' => 'Full HD airbrush bridal makeup, traditional Kandyan jewellery styling, and low jasmine bun.',
                    ],
                    [
                        'title' => 'Soft Glam Homecoming Styling',
                        'category' => 'Homecoming',
                        'client_name' => 'Navodya Jayasuriya',
                        'performed_date' => 'Oct 18, 2026',
                        'duration' => '2h 15m',
                        'price' => 'Rs. 28,500',
                        'rating' => 4.9,
                        'accent_color' => '#ec4899',
                        'description' => 'Dewy rose-gold makeup with Hollywood vintage waves and red saree draping.',
                    ],
                    [
                        'title' => 'Bridesmaid Party Glam & Curls',
                        'category' => 'Party Makeup',
                        'client_name' => 'Chathuri Silva',
                        'performed_date' => 'Oct 15, 2026',
                        'duration' => '1h 30m',
                        'price' => 'Rs. 12,500',
                        'rating' => 5.0,
                        'accent_color' => '#6366f1',
                        'description' => 'Natural glowing skin finish with textured half-up braided hairstyle.',
                    ],
                ],
            ],
            [
                'user' => [
                    'name' => 'Roshan Mendis',
                    'email' => 'roshan@salonhq.com',
                    'password' => 'password',
                    'phone_number' => '071 845 2219',
                    'gender' => 'male',
                    'role' => 'worker',
                    'email_verified_at' => now(),
                ],
                'profile' => [
                    'job_title' => 'Master Barber & Men\'s Stylist',
                    'category' => 'Barbers',
                    'serves_gender' => 'male',
                    'availability_status' => 'in_session',
                    'experience' => '6 Years',
                    'joined_date' => 'June 2021',
                    'rating' => 4.8,
                    'reviews_count' => 215,
                    'completed_works' => 940,
                    'today_bookings' => 7,
                    'monthly_revenue' => 'Rs. 320,000',
                    'repeat_clients' => '88%',
                    'avatar_gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
                    'bio' => 'Precision men\'s grooming specialist experienced in skin fades, beard sculpting, hot towel razor shaves, and modern textured crops.',
                    'specialties' => ['Skin Fade', 'Beard Sculpting', 'Hot Towel Shave', 'Men\'s Hair Color', 'Scalp Detox'],
                    'schedule' => [
                        'Tue - Sun' => '09:00 AM - 07:30 PM',
                        'Monday' => 'Off Duty',
                    ],
                ],
                'works' => [
                    [
                        'title' => 'Executive Skin Fade & Beard Sculpt',
                        'category' => 'Men\'s Grooming',
                        'client_name' => 'Dilshan Silva',
                        'performed_date' => 'Oct 22, 2026',
                        'duration' => '45m',
                        'price' => 'Rs. 3,200',
                        'rating' => 5.0,
                        'accent_color' => '#0ea5e9',
                        'description' => 'Seamless mid-skin fade with razor line-up and hot towel beard conditioning.',
                    ],
                    [
                        'title' => 'Groom\'s Luxury Makeover Package',
                        'category' => 'Groom Package',
                        'client_name' => 'Kasun Bandara',
                        'performed_date' => 'Oct 19, 2026',
                        'duration' => '2h 00m',
                        'price' => 'Rs. 14,500',
                        'rating' => 4.9,
                        'accent_color' => '#2563eb',
                        'description' => 'Restyle haircut, charcoal detox facial, beard trim, and natural matte styling.',
                    ],
                ],
            ],
            [
                'user' => [
                    'name' => 'Amaya Jayawardena',
                    'email' => 'amaya@salonhq.com',
                    'password' => 'password',
                    'phone_number' => '076 992 1044',
                    'gender' => 'female',
                    'role' => 'worker',
                    'email_verified_at' => now(),
                ],
                'profile' => [
                    'job_title' => 'Creative Hair Color & Keratin Specialist',
                    'category' => 'Hair Stylists',
                    'serves_gender' => 'both',
                    'availability_status' => 'available',
                    'experience' => '5 Years',
                    'joined_date' => 'January 2022',
                    'rating' => 4.9,
                    'reviews_count' => 112,
                    'completed_works' => 480,
                    'today_bookings' => 3,
                    'monthly_revenue' => 'Rs. 410,000',
                    'repeat_clients' => '85%',
                    'avatar_gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'bio' => 'Unisex color transformation expert trained in French balayage, foliage techniques, Brazilian keratin smoothing, and Olaplex bond repair treatments.',
                    'specialties' => ['Balayage & Ombré', 'Brazilian Keratin', 'Root Melt', 'Olaplex Repair', 'Precision Layer Cut'],
                    'schedule' => [
                        'Mon - Sat' => '09:30 AM - 06:30 PM',
                        'Sunday' => 'Off Duty',
                    ],
                ],
                'works' => [
                    [
                        'title' => 'Caramel Melt Balayage & Butterfly Cut',
                        'category' => 'Hair Color',
                        'client_name' => 'Sachini Fernando',
                        'performed_date' => 'Oct 21, 2026',
                        'duration' => '3h 15m',
                        'price' => 'Rs. 22,000',
                        'rating' => 5.0,
                        'accent_color' => '#10b981',
                        'description' => 'Hand-painted warm caramel balayage with face-framing layers and gloss toner.',
                    ],
                    [
                        'title' => 'Brazilian Keratin Smoothing Therapy',
                        'category' => 'Hair Treatment',
                        'client_name' => 'Minoli De Silva',
                        'performed_date' => 'Oct 18, 2026',
                        'duration' => '2h 45m',
                        'price' => 'Rs. 16,000',
                        'rating' => 4.9,
                        'accent_color' => '#059669',
                        'description' => 'Frizz-free mirror shine keratin infusion with split-end dusting.',
                    ],
                ],
            ],
            [
                'user' => [
                    'name' => 'Nadeesha Karunaratne',
                    'email' => 'nadeesha@salonhq.com',
                    'password' => 'password',
                    'phone_number' => '077 203 5511',
                    'gender' => 'female',
                    'role' => 'worker',
                    'email_verified_at' => now(),
                ],
                'profile' => [
                    'job_title' => 'Senior Esthetician & Skin Therapist',
                    'category' => 'Estheticians & Nails',
                    'serves_gender' => 'both',
                    'availability_status' => 'on_break',
                    'experience' => '6 Years',
                    'joined_date' => 'September 2021',
                    'rating' => 4.7,
                    'reviews_count' => 94,
                    'completed_works' => 510,
                    'today_bookings' => 4,
                    'monthly_revenue' => 'Rs. 275,000',
                    'repeat_clients' => '82%',
                    'avatar_gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    'bio' => 'Licensed unisex skin care therapist specializing in medical-grade HydraFacials, LED light therapy, chemical peels, and relaxing aromatherapy spa rituals.',
                    'specialties' => ['HydraFacial MD', 'Anti-Aging Facial', 'LED Therapy', 'Dermaplaning', 'Full Body Waxing'],
                    'schedule' => [
                        'Mon - Sat' => '09:00 AM - 06:00 PM',
                        'Sunday' => '10:00 AM - 04:00 PM',
                    ],
                ],
                'works' => [
                    [
                        'title' => '7-Step Platinum HydraFacial & LED Glow',
                        'category' => 'Skin Care',
                        'client_name' => 'Tharindu Jayasinghe',
                        'performed_date' => 'Oct 20, 2026',
                        'duration' => '1h 15m',
                        'price' => 'Rs. 8,500',
                        'rating' => 4.9,
                        'accent_color' => '#f59e0b',
                        'description' => 'Deep pore vortex extraction, hyaluronic peptide infusion, and red LED collagen boost.',
                    ],
                ],
            ],
            [
                'user' => [
                    'name' => 'Shenal Wickramasinghe',
                    'email' => 'shenal@salonhq.com',
                    'password' => 'password',
                    'phone_number' => '071 554 9082',
                    'gender' => 'male',
                    'role' => 'worker',
                    'email_verified_at' => now(),
                ],
                'profile' => [
                    'job_title' => 'Senior Hair Stylist & Restyle Expert',
                    'category' => 'Hair Stylists',
                    'serves_gender' => 'both',
                    'availability_status' => 'available',
                    'experience' => '4 Years',
                    'joined_date' => 'April 2023',
                    'rating' => 4.8,
                    'reviews_count' => 76,
                    'completed_works' => 330,
                    'today_bookings' => 3,
                    'monthly_revenue' => 'Rs. 240,000',
                    'repeat_clients' => '79%',
                    'avatar_gradient' => 'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
                    'bio' => 'Passionate about modern precision haircuts for men and women, blow-dry volume styling, hair botox treatments, and editorial runway looks.',
                    'specialties' => ['Feather & Layer Cuts', 'Hair Botox', 'Blowout Styling', 'Scalp Spa', 'Hair Extensions'],
                    'schedule' => [
                        'Wed - Mon' => '09:00 AM - 06:30 PM',
                        'Tuesday' => 'Off Duty',
                    ],
                ],
                'works' => [
                    [
                        'title' => 'Voluminous Butterfly Cut & Blowout',
                        'category' => 'Hair Styling',
                        'client_name' => 'Malki Dissanayake',
                        'performed_date' => 'Oct 21, 2026',
                        'duration' => '1h 00m',
                        'price' => 'Rs. 4,500',
                        'rating' => 4.9,
                        'accent_color' => '#ec4899',
                        'description' => 'Face-framing curtain bangs with bouncy round-brush blowout.',
                    ],
                ],
            ],
            [
                'user' => [
                    'name' => 'Tashya Rodrigo',
                    'email' => 'tashya@salonhq.com',
                    'password' => 'password',
                    'phone_number' => '070 318 4421',
                    'gender' => 'female',
                    'role' => 'worker',
                    'email_verified_at' => now(),
                ],
                'profile' => [
                    'job_title' => 'Nail Artist & Lash Technician',
                    'category' => 'Estheticians & Nails',
                    'serves_gender' => 'female',
                    'availability_status' => 'available',
                    'experience' => '3 Years',
                    'joined_date' => 'August 2024',
                    'rating' => 4.9,
                    'reviews_count' => 64,
                    'completed_works' => 290,
                    'today_bookings' => 5,
                    'monthly_revenue' => 'Rs. 210,000',
                    'repeat_clients' => '89%',
                    'avatar_gradient' => 'linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%)',
                    'bio' => 'Creative nail technician and certified Russian volume eyelash extension artist delivering intricate nail art, luxury spa pedicures, and brow lamination.',
                    'specialties' => ['Acrylic & Gel Nails', '3D Nail Art', 'Russian Lash Extensions', 'Brow Lamination', 'Spa Pedicure'],
                    'schedule' => [
                        'Mon - Sat' => '10:00 AM - 07:00 PM',
                        'Sunday' => 'Off Duty',
                    ],
                ],
                'works' => [
                    [
                        'title' => 'Chrome Glazed Donut Gel Manicure',
                        'category' => 'Nail Art',
                        'client_name' => 'Dinithi Alwis',
                        'performed_date' => 'Oct 21, 2026',
                        'duration' => '1h 30m',
                        'price' => 'Rs. 5,800',
                        'rating' => 5.0,
                        'accent_color' => '#8b5cf6',
                        'description' => 'Almond-shaped BIAB gel overlay finished with pearl chrome glaze.',
                    ],
                    [
                        'title' => 'Hybrid Eyelash Extensions & Brow Lift',
                        'category' => 'Lashes & Brows',
                        'client_name' => 'Piumi Hettiarachchi',
                        'performed_date' => 'Oct 19, 2026',
                        'duration' => '1h 45m',
                        'price' => 'Rs. 7,200',
                        'rating' => 4.9,
                        'accent_color' => '#ec4899',
                        'description' => 'Wispy cat-eye hybrid lash set with keratin brow lamination and tint.',
                    ],
                ],
            ],
        ];

        foreach ($workers as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['user']['email']],
                $item['user']
            );

            $staff = Staff::updateOrCreate(
                ['user_id' => $user->id],
                $item['profile']
            );

            foreach ($item['works'] as $workData) {
                StaffWork::updateOrCreate(
                    [
                        'staff_id' => $staff->id,
                        'title' => $workData['title'],
                    ],
                    $workData
                );
            }
        }
    }
}

