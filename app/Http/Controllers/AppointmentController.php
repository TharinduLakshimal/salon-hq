<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Display the salon appointments management UI backed by real MySQL records.
     */
    public function index(Request $request): View
    {
        $staffMembers = Staff::with(['user', 'category'])->orderBy('id')->get();
        $stylistsList = $staffMembers->map(fn (Staff $member): array => [
            'id' => $member->id,
            'name' => $member->user?->name ?? 'Salon Stylist',
            'initials' => $member->initials,
            'gradient' => $member->avatar_gradient ?: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'category_id' => $member->category_id,
            'category' => $member->category?->name ?? 'Hair Stylists',
            'serves_gender' => $member->serves_gender ?? 'both',
        ])->values()->all();

        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $appointments = Appointment::with(['client', 'staff.user', 'category'])
            ->whereDate('appointment_date', '>=', now()->startOfWeek())
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get()
            ->map(fn (Appointment $appointment): array => $this->formatAppointment($appointment))
            ->values()
            ->all();

        $services = Service::select('id', 'name', 'price', 'duration_minutes', 'target_gender', 'category_id', 'is_package')->get();
        $categories = Category::select('id', 'name')->orderBy('id')->get();

        $openModalOnLoad = $request->boolean('new');

        return view('appointments.index', compact(
            'staffMembers',
            'stylistsList',
            'appointments',
            'services',
            'categories',
            'today',
            'tomorrow',
            'openModalOnLoad'
        ));
    }

    /**
     * Store a newly booked appointment (registered client or walk-in guest) and return JSON.
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'client_phone' => preg_replace('/[\s\-()]+/', '', (string) $request->input('client_phone')),
        ]);

        $validated = $request->validate([
            'client_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'client_name' => ['required', 'string', 'max:150'],
            'client_phone' => ['required', 'string', 'regex:/^(?:\+94|0)7\d{8}$/'],
            'client_gender' => ['required', Rule::in(['female', 'male', 'other'])],
            'staff_id' => ['required', 'integer', Rule::exists('staff', 'id')],
            'service_name' => ['required', 'string', 'max:150'],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'category' => ['nullable', 'string', 'max:80'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:600'],
            'price' => ['required', 'numeric', 'min:0'],
            'booking_type' => ['required', Rule::in([
                'walk_in',
                'phone_desk',
                'online_booking',
                'Walk-In',
                'Phone Desk',
                'Online Booking',
            ])],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'string', 'regex:/^(?:\d{1,2}:\d{2}\s*(?:AM|PM)|\d{2}:\d{2}(?::\d{2})?)$/i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $staff = Staff::with(['user', 'category'])->findOrFail($validated['staff_id']);

        $clientUser = ! empty($validated['client_id'])
            ? User::find($validated['client_id'])
            : null;

        $categoryId = ! empty($validated['category_id'])
            ? (int) $validated['category_id']
            : (! empty($validated['category'])
                ? Category::where('name', $validated['category'])->value('id')
                : null);

        $normalizedBookingType = match ($validated['booking_type']) {
            'Online Booking', 'online_booking' => 'online_booking',
            'Phone Desk', 'phone_desk' => 'phone_desk',
            default => 'walk_in',
        };

        $parsedStart = Carbon::parse($validated['start_time']);
        $durationMinutes = (int) $validated['duration_minutes'];
        $parsedEnd = $parsedStart->copy()->addMinutes($durationMinutes);

        $year = Carbon::parse($validated['date'])->format('Y');
        $nextSequence = ((int) Appointment::max('id')) + 101;
        do {
            $bookingCode = sprintf('BK-%s-%04d', $year, $nextSequence);
            $nextSequence++;
        } while (Appointment::where('booking_code', $bookingCode)->exists());

        $appointment = Appointment::create([
            'booking_code' => $bookingCode,
            'client_id' => $clientUser?->id,
            'staff_id' => $staff->id,
            'category_id' => $categoryId ?? $staff->category_id,
            'client_name' => $clientUser?->name ?? trim($validated['client_name']),
            'client_phone' => $validated['client_phone'],
            'client_gender' => $validated['client_gender'],
            'booking_type' => $normalizedBookingType,
            'service_name' => $validated['service_name'],
            'duration_minutes' => $durationMinutes,
            'appointment_date' => $validated['date'],
            'start_time' => $parsedStart->format('H:i:s'),
            'end_time' => $parsedEnd->format('H:i:s'),
            'price' => $validated['price'],
            'status' => 'confirmed',
            'notes' => $validated['notes'] ?? null,
        ]);

        $appointment->load(['client', 'staff.user', 'category']);

        return response()->json([
            'message' => "Booking {$appointment->booking_code} confirmed for {$appointment->display_name}.",
            'appointment' => $this->formatAppointment($appointment),
        ], 200);
    }

    /**
     * Update an existing appointment's lifecycle status and return JSON.
     */
    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])],
        ]);

        $appointment->update([
            'status' => $validated['status'],
        ]);

        $appointment->load(['client', 'staff.user', 'category']);

        return response()->json([
            'message' => "Booking {$appointment->booking_code} marked as {$appointment->status_label}.",
            'appointment' => $this->formatAppointment($appointment),
        ], 200);
    }

    /**
     * Transform an Appointment Eloquent model into the payload consumed by createAppointmentsBoard.
     *
     * @return array<string, mixed>
     */
    private function formatAppointment(Appointment $appointment): array
    {
        return [
            'id' => $appointment->id,
            'booking_code' => $appointment->booking_code,
            'client_id' => $appointment->client_id,
            'client_name' => $appointment->display_name,
            'client_phone' => $appointment->client_phone,
            'client_gender' => $appointment->client_gender,
            'booking_type' => $appointment->booking_type_label,
            'service_name' => $appointment->service_name,
            'category' => $appointment->category?->name ?? $appointment->staff?->category?->name ?? 'Hair Stylists',
            'duration_minutes' => (int) $appointment->duration_minutes,
            'staff_id' => $appointment->staff_id,
            'stylist_name' => $appointment->staff?->user?->name ?? 'Salon Stylist',
            'stylist_initials' => $appointment->staff?->initials ?? 'SS',
            'stylist_gradient' => $appointment->staff?->avatar_gradient ?: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'date' => $appointment->appointment_date->toDateString(),
            'start_time' => $appointment->formatted_start_time,
            'end_time' => $appointment->formatted_end_time,
            'hour_key' => $appointment->hour_key,
            'price' => (float) $appointment->price,
            'status' => $appointment->status,
            'notes' => $appointment->notes ?? '',
        ];
    }
}
