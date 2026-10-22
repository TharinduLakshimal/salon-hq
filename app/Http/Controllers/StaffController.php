<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display all staff members from the database.
     */
    public function index(): View
    {
        $staffMembers = Staff::with(['user', 'works'])->orderBy('id')->get();

        return view('staff.index', compact('staffMembers'));
    }

    /**
     * Store a newly created staff member (User + Staff Profile) in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:25'],
            'gender' => ['required', 'in:male,female,other'],
            'job_title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'serves_gender' => ['required', 'in:both,female,male'],
            'experience' => ['required', 'string', 'max:50'],
            'specialties' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
        ]);

        $skills = ! empty($validated['specialties'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['specialties']))))
            : ['Salon Styling', 'Client Care'];

        $gradients = [
            'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
            'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
        ];

        DB::transaction(function () use ($validated, $skills, $gradients) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => 'password',
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'role' => 'worker',
                'email_verified_at' => now(),
            ]);

            Staff::create([
                'user_id' => $user->id,
                'job_title' => $validated['job_title'],
                'category' => $validated['category'],
                'serves_gender' => $validated['serves_gender'],
                'availability_status' => 'available',
                'experience' => $validated['experience'],
                'joined_date' => now()->format('F Y'),
                'rating' => 5.0,
                'reviews_count' => 0,
                'completed_works' => 0,
                'today_bookings' => 0,
                'monthly_revenue' => 'Rs. 0',
                'repeat_clients' => '100%',
                'avatar_gradient' => $gradients[array_rand($gradients)],
                'bio' => $validated['bio'] ?: 'Dedicated salon specialist at Salon HQ.',
                'specialties' => $skills,
                'schedule' => [
                    'Mon - Sat' => '09:00 AM - 06:30 PM',
                    'Sunday' => 'Off Duty',
                ],
            ]);
        });

        return redirect()
            ->route('staff.index')
            ->with('success', 'New staff member added to the database successfully!');
    }

    /**
     * Display a specific staff member's profile and portfolio works from the database.
     */
    public function show(int $id): View
    {
        $staff = Staff::with(['user', 'works'])->findOrFail($id);

        return view('staff.show', compact('staff'));
    }
}
