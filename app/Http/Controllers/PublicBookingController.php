<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PublicBookingController extends Controller
{
    /**
     * Display the public client-facing online booking portal.
     */
    public function index(): View
    {
        $categories = Category::with([
            'services' => fn ($query) => $query->orderBy('name'),
            'staff.user',
        ])->orderBy('id')->get();

        $services = Service::all();

        $allStaff = Staff::with(['user', 'category'])
            ->where('availability_status', '!=', 'off_duty')
            ->orderBy('id')
            ->get();

        $bookedAppointments = Appointment::whereDate('appointment_date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->select(['id', 'staff_id', 'appointment_date', 'start_time', 'end_time'])
            ->get()
            ->map(function ($appt) {
                return [
                    'staff_id' => (int) $appt->staff_id,
                    'date' => Carbon::parse($appt->appointment_date)->format('Y-m-d'),
                    'start_time' => Carbon::parse($appt->start_time)->format('h:i A'),
                    'end_time' => Carbon::parse($appt->end_time)->format('h:i A'),
                    'start_raw' => $appt->start_time,
                    'end_raw' => $appt->end_time,
                ];
            });

        return view('public.booking', compact('categories', 'services', 'allStaff', 'bookedAppointments'));
    }

    /**
     * Real-time API to check booked time slots for a specific stylist on a given date.
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $staffId = $request->query('staff_id');
        $date = $request->query('date');

        if (! $staffId || ! $date) {
            return response()->json(['booked_slots' => []]);
        }

        $formattedDate = Carbon::parse($date)->toDateString();

        $booked = Appointment::where('staff_id', $staffId)
            ->whereDate('appointment_date', $formattedDate)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($appt) {
                return [
                    'start_time' => Carbon::parse($appt->start_time)->format('h:i A'),
                    'end_time' => Carbon::parse($appt->end_time)->format('h:i A'),
                    'start_raw' => $appt->start_time,
                    'end_raw' => $appt->end_time,
                ];
            });

        return response()->json(['booked_slots' => $booked]);
    }

    /**
     * Store a new online booking from a public guest or registered client.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'client_phone' => preg_replace('/[\s\-()]+/', '', (string) $request->input('client_phone')),
        ]);

        $validated = $request->validate([
            'service_id' => ['nullable', 'integer', Rule::exists('services', 'id')],
            'service_name' => ['required_without:service_id', 'nullable', 'string', 'max:150'],
            'staff_id' => ['required', 'integer', Rule::exists('staff', 'id')],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'string', 'regex:/^(?:\d{1,2}:\d{2}\s*(?:AM|PM)|\d{2}:\d{2}(?::\d{2})?)$/i'],
            'client_name' => ['required', 'string', 'max:150'],
            'client_phone' => ['required', 'string', 'regex:/^(?:\+94|0)7\d{8}$/'],
            'client_gender' => ['required', Rule::in(['female', 'male', 'other'])],
            'client_email' => ['nullable', 'email', 'max:150'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = ! empty($validated['service_id'])
            ? Service::with('category')->find($validated['service_id'])
            : Service::where('name', $validated['service_name'])->first();

        $staff = Staff::with(['user', 'category'])->findOrFail($validated['staff_id']);
        $categoryId = $service?->category_id ?? $staff->category_id;
        $serviceName = $service?->name ?? $validated['service_name'];
        $servicePrice = $service ? (float) $service->price : 0.00;
        $durationMinutes = $service ? (int) $service->duration_minutes : 45;

        // Resolve client User: check authenticated session, existing by phone, or create new client User
        $clientUser = auth()->user();

        if (! $clientUser) {
            $normalizedPhone = $validated['client_phone'];
            $candidates = [$normalizedPhone];
            if (str_starts_with($normalizedPhone, '0') && strlen($normalizedPhone) === 10) {
                $candidates[] = '+94'.substr($normalizedPhone, 1);
            } elseif (str_starts_with($normalizedPhone, '+94') && strlen($normalizedPhone) === 12) {
                $candidates[] = '0'.substr($normalizedPhone, 3);
            }

            $clientUser = User::whereIn('phone_number', $candidates)->first();

            if (! $clientUser) {
                $email = ! empty($validated['client_email'])
                    ? $validated['client_email']
                    : 'client_'.preg_replace('/\D/', '', $normalizedPhone).'@guest.salonhq.local';

                if (User::where('email', $email)->exists()) {
                    $email = 'client_'.preg_replace('/\D/', '', $normalizedPhone).'_'.time().'@guest.salonhq.local';
                }

                $clientUser = User::create([
                    'name' => trim($validated['client_name']),
                    'email' => $email,
                    'password' => 'password',
                    'phone_number' => $normalizedPhone,
                    'gender' => $validated['client_gender'],
                    'role' => 'client',
                    'email_verified_at' => now(),
                ]);
            }
        }

        // Calculate appointment schedule times
        $parsedStart = Carbon::parse($validated['start_time']);
        $parsedEnd = $parsedStart->copy()->addMinutes($durationMinutes);

        // Anti Double-Booking Check: Verify this specific stylist is not already booked at this time slot
        $conflict = Appointment::where('staff_id', $staff->id)
            ->whereDate('appointment_date', Carbon::parse($validated['date'])->toDateString())
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($parsedStart, $parsedEnd) {
                $startStr = $parsedStart->format('H:i:s');
                $endStr = $parsedEnd->format('H:i:s');
                $q->where('start_time', $startStr)
                    ->orWhere(function ($sub) use ($startStr, $endStr) {
                        $sub->where('start_time', '<', $endStr)
                            ->where('end_time', '>', $startStr);
                    });
            })
            ->exists();

        if ($conflict) {
            $stylistName = $staff->user?->name ?? 'The selected barber/stylist';

            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => "{$stylistName} is already booked at {$validated['start_time']} on this date. Please choose another available time slot.",
                ]);
        }

        // Generate unique booking code: BK-YYYY-XXXX
        $year = Carbon::parse($validated['date'])->format('Y');
        $nextSequence = ((int) Appointment::max('id')) + 101;
        do {
            $bookingCode = sprintf('BK-%s-%04d', $year, $nextSequence);
            $nextSequence++;
        } while (Appointment::where('booking_code', $bookingCode)->exists());

        $appointment = Appointment::create([
            'booking_code' => $bookingCode,
            'client_id' => $clientUser->id,
            'staff_id' => $staff->id,
            'category_id' => $categoryId,
            'client_name' => $clientUser->name,
            'client_phone' => $validated['client_phone'],
            'client_gender' => $validated['client_gender'],
            'booking_type' => 'online_booking',
            'service_name' => $serviceName,
            'duration_minutes' => $durationMinutes,
            'appointment_date' => $validated['date'],
            'start_time' => $parsedStart->format('H:i:s'),
            'end_time' => $parsedEnd->format('H:i:s'),
            'price' => $servicePrice,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('booking.success', ['code' => $appointment->booking_code])
            ->with('success', 'Your appointment has been successfully scheduled!')
            ->with('booking_code', $appointment->booking_code);
    }

    /**
     * Display the public booking success confirmation page.
     */
    public function success(Request $request): View
    {
        $code = $request->query('code', session('booking_code'));
        $appointment = null;

        if ($code) {
            $appointment = Appointment::with(['client', 'staff.user', 'category'])
                ->where('booking_code', $code)
                ->first();
        }

        return view('public.success', compact('appointment'));
    }
}
