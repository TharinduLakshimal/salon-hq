<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Display the salon appointments management UI.
     */
    public function index(Request $request): View
    {
        $staffMembers = Staff::with(['user', 'category'])->orderBy('id')->get();
        $stylistsList = $staffMembers->map(fn (Staff $member): array => [
            'name' => $member->user?->name ?? 'Salon Stylist',
            'initials' => $member->initials,
            'gradient' => $member->avatar_gradient ?: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'category' => $member->category?->name ?? 'Hair Stylists',
            'serves_gender' => $member->serves_gender ?? 'both',
        ])->values()->all();

        if ($stylistsList === []) {
            $stylistsList = [
                [
                    'name' => 'Kavindi Senanayake',
                    'initials' => 'KS',
                    'gradient' => 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                    'category' => 'Bridal & Makeup',
                    'serves_gender' => 'female',
                ],
                [
                    'name' => 'Roshan Mendis',
                    'initials' => 'RM',
                    'gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
                    'category' => 'Barbers',
                    'serves_gender' => 'male',
                ],
            ];
        }

        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $appointments = [
            [
                'id' => 1,
                'booking_code' => 'BK-2026-0101',
                'client_name' => 'Nethmi Perera',
                'client_phone' => '077 412 8930',
                'client_gender' => 'female',
                'booking_type' => 'Online Booking',
                'service_name' => 'Bridal Hair & HD Airbrush Makeup',
                'category' => 'Bridal & Makeup',
                'duration_minutes' => 210,
                'stylist_name' => 'Kavindi Senanayake',
                'stylist_initials' => 'KS',
                'stylist_gradient' => 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                'date' => $today,
                'start_time' => '09:30 AM',
                'end_time' => '01:00 PM',
                'hour_key' => '09:00 AM',
                'price' => 18500,
                'status' => 'in_progress',
                'notes' => 'Traditional Kandyan jasmine hair styling requested.',
            ],
            [
                'id' => 2,
                'booking_code' => 'BK-2026-0102',
                'client_name' => 'Dilshan Silva',
                'client_phone' => '071 845 2219',
                'client_gender' => 'male',
                'booking_type' => 'Walk-In',
                'service_name' => 'Executive Skin Fade & Beard Styling',
                'category' => 'Barbers',
                'duration_minutes' => 45,
                'stylist_name' => 'Roshan Mendis',
                'stylist_initials' => 'RM',
                'stylist_gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
                'date' => $today,
                'start_time' => '10:15 AM',
                'end_time' => '11:00 AM',
                'hour_key' => '10:00 AM',
                'price' => 3200,
                'status' => 'in_progress',
                'notes' => 'Hot towel shave finish.',
            ],
            [
                'id' => 3,
                'booking_code' => 'BK-2026-0103',
                'client_name' => 'Sachini Fernando',
                'client_phone' => '076 992 1044',
                'client_gender' => 'female',
                'booking_type' => 'Phone Desk',
                'service_name' => 'Brazilian Keratin Hair Treatment',
                'category' => 'Hair Stylists',
                'duration_minutes' => 165,
                'stylist_name' => 'Amaya Jayawardena',
                'stylist_initials' => 'AJ',
                'stylist_gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                'date' => $today,
                'start_time' => '11:00 AM',
                'end_time' => '01:45 PM',
                'hour_key' => '11:00 AM',
                'price' => 14000,
                'status' => 'confirmed',
                'notes' => 'Sulfate-free aftercare kit inquiry.',
            ],
            [
                'id' => 4,
                'booking_code' => 'BK-2026-0104',
                'client_name' => 'Tharindu Jayasinghe',
                'client_phone' => '077 203 5511',
                'client_gender' => 'male',
                'booking_type' => 'Online Booking',
                'service_name' => '7-Step Platinum HydraFacial',
                'category' => 'Estheticians & Nails',
                'duration_minutes' => 75,
                'stylist_name' => 'Nadeesha Karunaratne',
                'stylist_initials' => 'NK',
                'stylist_gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                'date' => $today,
                'start_time' => '01:30 PM',
                'end_time' => '02:45 PM',
                'hour_key' => '01:00 PM',
                'price' => 7500,
                'status' => 'confirmed',
                'notes' => 'Sensitive skin — mild enzyme peel only.',
            ],
            [
                'id' => 5,
                'booking_code' => 'BK-2026-0105',
                'client_name' => 'Hashini Wijeratne',
                'client_phone' => '070 331 6780',
                'client_gender' => 'female',
                'booking_type' => 'Online Booking',
                'service_name' => 'BIAB Gel Nail Extensions & Chrome Art',
                'category' => 'Estheticians & Nails',
                'duration_minutes' => 90,
                'stylist_name' => 'Tashya Rodrigo',
                'stylist_initials' => 'TR',
                'stylist_gradient' => 'linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%)',
                'date' => $today,
                'start_time' => '03:00 PM',
                'end_time' => '04:30 PM',
                'hour_key' => '03:00 PM',
                'price' => 5300,
                'status' => 'pending',
                'notes' => 'Almond shape pearl glaze.',
            ],
            [
                'id' => 6,
                'booking_code' => 'BK-2026-0106',
                'client_name' => 'Malki Dissanayake',
                'client_phone' => '071 609 3321',
                'client_gender' => 'female',
                'booking_type' => 'Walk-In',
                'service_name' => 'Butterfly Layer Cut & Blowout',
                'category' => 'Hair Stylists',
                'duration_minutes' => 60,
                'stylist_name' => 'Shenal Wickramasinghe',
                'stylist_initials' => 'SW',
                'stylist_gradient' => 'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
                'date' => $today,
                'start_time' => '09:00 AM',
                'end_time' => '10:00 AM',
                'hour_key' => '09:00 AM',
                'price' => 4500,
                'status' => 'completed',
                'notes' => 'Paid via card at POS.',
            ],
            [
                'id' => 7,
                'booking_code' => 'BK-2026-0107',
                'client_name' => 'Kasun Bandara',
                'client_phone' => '077 889 4510',
                'client_gender' => 'male',
                'booking_type' => 'Phone Desk',
                'service_name' => 'Groom Luxury Hair & Detox Facial Package',
                'category' => 'Barbers',
                'duration_minutes' => 120,
                'stylist_name' => 'Roshan Mendis',
                'stylist_initials' => 'RM',
                'stylist_gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
                'date' => $tomorrow,
                'start_time' => '10:00 AM',
                'end_time' => '12:00 PM',
                'hour_key' => '10:00 AM',
                'price' => 14500,
                'status' => 'confirmed',
                'notes' => 'Wedding trial consultation.',
            ],
            [
                'id' => 8,
                'booking_code' => 'BK-2026-0108',
                'client_name' => 'Chathuri Silva',
                'client_phone' => '076 410 7782',
                'client_gender' => 'female',
                'booking_type' => 'Online Booking',
                'service_name' => 'Party Glam Makeup & Soft Curls',
                'category' => 'Bridal & Makeup',
                'duration_minutes' => 90,
                'stylist_name' => 'Kavindi Senanayake',
                'stylist_initials' => 'KS',
                'stylist_gradient' => 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                'date' => $today,
                'start_time' => '04:30 PM',
                'end_time' => '06:00 PM',
                'hour_key' => '04:00 PM',
                'price' => 12500,
                'status' => 'cancelled',
                'notes' => 'Client requested reschedule to next weekend.',
            ],
        ];

        $serviceCatalog = [
            ['name' => 'Bridal Hair & HD Airbrush Makeup', 'category' => 'Bridal & Makeup', 'duration' => 210, 'price' => 18500, 'gender' => 'female'],
            ['name' => 'Party Glam Makeup & Soft Curls', 'category' => 'Bridal & Makeup', 'duration' => 90, 'price' => 12500, 'gender' => 'female'],
            ['name' => 'Executive Skin Fade & Beard Styling', 'category' => 'Barbers', 'duration' => 45, 'price' => 3200, 'gender' => 'male'],
            ['name' => 'Groom Luxury Hair & Detox Facial Package', 'category' => 'Barbers', 'duration' => 120, 'price' => 14500, 'gender' => 'male'],
            ['name' => 'Brazilian Keratin Hair Treatment', 'category' => 'Hair Stylists', 'duration' => 165, 'price' => 14000, 'gender' => 'both'],
            ['name' => 'Butterfly Layer Cut & Blowout', 'category' => 'Hair Stylists', 'duration' => 60, 'price' => 4500, 'gender' => 'both'],
            ['name' => '7-Step Platinum HydraFacial', 'category' => 'Estheticians & Nails', 'duration' => 75, 'price' => 7500, 'gender' => 'both'],
            ['name' => 'BIAB Gel Nail Extensions & Chrome Art', 'category' => 'Estheticians & Nails', 'duration' => 90, 'price' => 5300, 'gender' => 'female'],
        ];

        $openModalOnLoad = $request->boolean('new');

        return view('appointments.index', compact(
            'staffMembers',
            'stylistsList',
            'appointments',
            'serviceCatalog',
            'today',
            'tomorrow',
            'openModalOnLoad'
        ));
    }
}
