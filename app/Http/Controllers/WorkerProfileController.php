<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WorkerProfileController extends Controller
{
    /**
     * Display the authenticated worker's profile, performance metrics, and editing tools.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->isWorker(), 403, 'Unauthorized access.');

        $staff = $user->staffProfile()->with(['user', 'category'])->first();

        if (! $staff) {
            $staff = Staff::create([
                'user_id' => $user->id,
                'category_id' => null,
                'job_title' => 'Salon Stylist',
                'serves_gender' => 'both',
                'availability_status' => 'available',
                'experience' => '1 Year',
                'joined_date' => now()->format('F Y'),
                'commission_percentage' => 15.00,
                'rating' => 5.0,
                'reviews_count' => 0,
                'completed_works' => 0,
                'today_bookings' => 0,
                'monthly_revenue' => 0.00,
                'repeat_clients' => '100%',
                'avatar_gradient' => 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                'bio' => 'Salon specialist at Salon HQ.',
                'specialties' => ['Salon Styling', 'Client Care'],
            ]);

            $staff->load(['user', 'category']);
        }

        return view('worker.profile', compact('staff'));
    }

    /**
     * Update the authenticated worker's allowable personal and stylist info.
     * Strictly disallows altering commission_percentage, job_title, or category_id.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->isWorker(), 403, 'Unauthorized access.');

        $staff = $user->staffProfile;
        abort_unless($staff, 404, 'Worker staff profile not found.');

        $request->merge([
            'phone_number' => preg_replace('/[\s\-()]+/', '', (string) $request->input('phone_number')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone_number' => ['required', 'string', 'regex:/^(?:\+94|0)7\d{8}$/'],
            'gender' => ['required', Rule::in(['female', 'male', 'other'])],
            'bio' => ['nullable', 'string', 'max:1000'],
            'specialties' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Explicitly update only allowed User fields
        $userUpdates = [
            'name' => trim($validated['name']),
            'phone_number' => $validated['phone_number'],
            'gender' => $validated['gender'],
        ];

        if (! empty($validated['password'])) {
            $userUpdates['password'] = $validated['password'];
        }

        $user->update($userUpdates);

        // Explicitly update only allowed Staff fields (bio & specialties)
        $specialtiesList = ! empty($validated['specialties'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['specialties']))))
            : ($staff->specialties ?? ['Salon Styling']);

        $staff->update([
            'bio' => $validated['bio'] !== null ? trim($validated['bio']) : $staff->bio,
            'specialties' => $specialtiesList,
        ]);

        return redirect()->route('worker.profile')->with('status', 'Your profile details have been successfully updated.');
    }

    /**
     * Instantly update the authenticated worker's live availability status.
     */
    public function updateAvailability(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->isWorker(), 403, 'Unauthorized access.');

        $staff = $user->staffProfile;
        abort_unless($staff, 404, 'Worker staff profile not found.');

        $validated = $request->validate([
            'availability_status' => ['required', 'string'],
        ]);

        $statusMap = [
            'available' => 'available',
            'in_session' => 'in_session',
            'in session' => 'in_session',
            'on_break' => 'on_break',
            'on break' => 'on_break',
            'off_duty' => 'off_duty',
            'off duty' => 'off_duty',
        ];

        $normalizedKey = strtolower(trim($validated['availability_status']));
        $status = $statusMap[$normalizedKey] ?? null;

        if (! $status) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid availability status supplied.'], 422);
            }

            return back()->withErrors(['availability_status' => 'Invalid status supplied.']);
        }

        $staff->update([
            'availability_status' => $status,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Availability updated to {$staff->status_label}.",
                'status' => $staff->availability_status,
                'status_label' => $staff->status_label,
            ], 200);
        }

        return redirect()->route('worker.profile')->with('status', "Availability marked as {$staff->status_label}.");
    }
}
