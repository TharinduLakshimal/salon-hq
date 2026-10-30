<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display the salon clients CRM & directory UI.
     */
    public function index(Request $request): View
    {
        $staffMembers = Staff::with(['user', 'category'])->orderBy('id')->get();
        $stylistsList = $staffMembers->map(fn (Staff $member): array => [
            'name' => $member->user?->name ?? 'Salon Stylist',
            'category' => $member->category?->name ?? 'Hair Stylists',
            'serves_gender' => $member->serves_gender ?? 'both',
        ])->values()->all();

        if ($stylistsList === []) {
            $stylistsList = [
                ['name' => 'Kavindi Senanayake', 'category' => 'Bridal & Makeup', 'serves_gender' => 'female'],
                ['name' => 'Roshan Mendis', 'category' => 'Barbers', 'serves_gender' => 'male'],
                ['name' => 'Amaya Jayawardena', 'category' => 'Hair Stylists', 'serves_gender' => 'both'],
            ];
        }

        $clients = [
            [
                'id' => 1,
                'client_code' => 'CL-2026-0001',
                'name' => 'Nethmi Perera',
                'email' => 'nethmi.perera@gmail.com',
                'phone_number' => '077 412 8930',
                'gender' => 'female',
                'date_of_birth' => '1997-05-14',
                'address' => 'Colombo 07',
                'preferred_stylist' => 'Kavindi Senanayake',
                'membership_tier' => 'VIP Platinum',
                'loyalty_points' => 2450,
                'total_visits' => 18,
                'total_spent' => 164500,
                'last_visit' => now()->toDateString(),
                'hair_skin_notes' => 'Prefers ammonia-free hair gloss; Kandyan bridal trial completed. Hypoallergenic primer only.',
                'avatar_initials' => 'NP',
                'avatar_gradient' => 'linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%)',
                'recent_services' => [
                    ['service' => 'Bridal Hair & HD Airbrush Makeup', 'date' => 'Today', 'amount' => 18500],
                    ['service' => 'Luxury Spa Manicure & Pedicure', 'date' => '2 weeks ago', 'amount' => 6500],
                ],
            ],
            [
                'id' => 2,
                'client_code' => 'CL-2026-0002',
                'name' => 'Dilshan Silva',
                'email' => 'dilshan.silva@outlook.com',
                'phone_number' => '071 845 2219',
                'gender' => 'male',
                'date_of_birth' => '1993-10-28',
                'address' => 'Rajagiriya',
                'preferred_stylist' => 'Roshan Mendis',
                'membership_tier' => 'Gold Member',
                'loyalty_points' => 1320,
                'total_visits' => 14,
                'total_spent' => 48200,
                'last_visit' => now()->toDateString(),
                'hair_skin_notes' => '0.5mm mid skin fade; sensitive neck area — finish with cooling aloe balm after hot towel shave.',
                'avatar_initials' => 'DS',
                'avatar_gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
                'recent_services' => [
                    ['service' => 'Executive Skin Fade & Beard Styling', 'date' => 'Today', 'amount' => 3200],
                    ['service' => 'Charcoal Express Detox Facial', 'date' => '3 weeks ago', 'amount' => 4800],
                ],
            ],
            [
                'id' => 3,
                'client_code' => 'CL-2026-0003',
                'name' => 'Sachini Fernando',
                'email' => 'sachini.fdo@gmail.com',
                'phone_number' => '076 992 1044',
                'gender' => 'female',
                'date_of_birth' => '1995-02-19',
                'address' => 'Nugegoda',
                'preferred_stylist' => 'Amaya Jayawardena',
                'membership_tier' => 'VIP Platinum',
                'loyalty_points' => 2180,
                'total_visits' => 15,
                'total_spent' => 138000,
                'last_visit' => now()->subDays(4)->toDateString(),
                'hair_skin_notes' => 'Formula: Wella Koleston 7/71 Caramel Balayage. Uses sulfate-free keratin home care.',
                'avatar_initials' => 'SF',
                'avatar_gradient' => 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)',
                'recent_services' => [
                    ['service' => 'Brazilian Keratin Hair Treatment', 'date' => '4 days ago', 'amount' => 14000],
                    ['service' => 'French Balayage & Olaplex Bond', 'date' => '6 weeks ago', 'amount' => 22500],
                ],
            ],
            [
                'id' => 4,
                'client_code' => 'CL-2026-0004',
                'name' => 'Tharindu Jayasinghe',
                'email' => 'tharindu.j@gmail.com',
                'phone_number' => '077 203 5511',
                'gender' => 'male',
                'date_of_birth' => '1991-08-03',
                'address' => 'Nawala',
                'preferred_stylist' => 'Nadeesha Karunaratne',
                'membership_tier' => 'Gold Member',
                'loyalty_points' => 980,
                'total_visits' => 9,
                'total_spent' => 64500,
                'last_visit' => now()->subDays(9)->toDateString(),
                'hair_skin_notes' => 'Reactive skin — avoid strong glycolic acids; use mild enzyme peel during HydraFacial.',
                'avatar_initials' => 'TJ',
                'avatar_gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                'recent_services' => [
                    ['service' => '7-Step Platinum HydraFacial', 'date' => '9 days ago', 'amount' => 7500],
                    ['service' => 'Classic Scissor Cut & Scalp Spa', 'date' => '1 month ago', 'amount' => 4200],
                ],
            ],
            [
                'id' => 5,
                'client_code' => 'CL-2026-0005',
                'name' => 'Hashini Wijeratne',
                'email' => 'hashini.w@yahoo.com',
                'phone_number' => '070 331 6780',
                'gender' => 'female',
                'date_of_birth' => '1999-11-22',
                'address' => 'Battaramulla',
                'preferred_stylist' => 'Tashya Rodrigo',
                'membership_tier' => 'Regular',
                'loyalty_points' => 540,
                'total_visits' => 6,
                'total_spent' => 31800,
                'last_visit' => now()->subDays(12)->toDateString(),
                'hair_skin_notes' => 'Prefers BIAB builder gel in soft almond shape; pearl chrome finish.',
                'avatar_initials' => 'HW',
                'avatar_gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                'recent_services' => [
                    ['service' => 'BIAB Gel Nail Extensions & Chrome Art', 'date' => '12 days ago', 'amount' => 5300],
                ],
            ],
            [
                'id' => 6,
                'client_code' => 'CL-2026-0006',
                'name' => 'Kasun Bandara',
                'email' => 'kasun.bandara@gmail.com',
                'phone_number' => '077 889 4510',
                'gender' => 'male',
                'date_of_birth' => '1994-04-11',
                'address' => 'Dehiwala',
                'preferred_stylist' => 'Roshan Mendis',
                'membership_tier' => 'Regular',
                'loyalty_points' => 460,
                'total_visits' => 5,
                'total_spent' => 29500,
                'last_visit' => now()->subDays(18)->toDateString(),
                'hair_skin_notes' => 'Upcoming wedding groom package scheduled; prefers natural matte hair clay.',
                'avatar_initials' => 'KB',
                'avatar_gradient' => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
                'recent_services' => [
                    ['service' => 'Groom Consultation & Beard Sculpt', 'date' => '18 days ago', 'amount' => 3500],
                ],
            ],
            [
                'id' => 7,
                'client_code' => 'CL-2026-0007',
                'name' => 'Malki Dissanayake',
                'email' => 'malki.d@gmail.com',
                'phone_number' => '071 609 3321',
                'gender' => 'female',
                'date_of_birth' => '2001-07-08',
                'address' => 'Maharagama',
                'preferred_stylist' => 'Shenal Wickramasinghe',
                'membership_tier' => 'New Client',
                'loyalty_points' => 150,
                'total_visits' => 2,
                'total_spent' => 9000,
                'last_visit' => now()->toDateString(),
                'hair_skin_notes' => 'Medium-long wavy hair; likes curtain bangs and bouncy round-brush blowout.',
                'avatar_initials' => 'MD',
                'avatar_gradient' => 'linear-gradient(135deg, #e11d48 0%, #be123c 100%)',
                'recent_services' => [
                    ['service' => 'Butterfly Layer Cut & Blowout', 'date' => 'Today', 'amount' => 4500],
                ],
            ],
            [
                'id' => 8,
                'client_code' => 'CL-2026-0008',
                'name' => 'Shehan Gunawardena',
                'email' => 'shehan.g@gmail.com',
                'phone_number' => '076 512 8840',
                'gender' => 'male',
                'date_of_birth' => '1998-01-30',
                'address' => 'Mount Lavinia',
                'preferred_stylist' => 'Shenal Wickramasinghe',
                'membership_tier' => 'New Client',
                'loyalty_points' => 100,
                'total_visits' => 1,
                'total_spent' => 4500,
                'last_visit' => now()->subDays(6)->toDateString(),
                'hair_skin_notes' => 'First-time textured crop & scalp detox client.',
                'avatar_initials' => 'SG',
                'avatar_gradient' => 'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)',
                'recent_services' => [
                    ['service' => 'Textured Crop & Scalp Detox', 'date' => '6 days ago', 'amount' => 4500],
                ],
            ],
        ];

        $openModalOnLoad = $request->boolean('new');
        $isOwner = $request->user()?->isOwner() ?? false;

        return view('clients.index', compact(
            'staffMembers',
            'stylistsList',
            'clients',
            'openModalOnLoad',
            'isOwner'
        ));
    }
}
