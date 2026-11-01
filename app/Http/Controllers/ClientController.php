<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display salon clients loaded directly from the MySQL database.
     */
    public function index(Request $request): View
    {
        $staffMembers = Staff::with(['user', 'category'])->orderBy('id')->get();
        $stylistsList = $staffMembers->map(fn (Staff $member): array => [
            'name' => $member->user?->name ?? 'Salon Stylist',
            'category' => $member->category?->name ?? 'Hair Stylists',
            'serves_gender' => $member->serves_gender ?? 'both',
        ])->values()->all();

        $gradients = [
            'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
            'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
            'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
            'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)',
        ];

        $clients = User::where('role', 'client')
            ->withCount(['appointments as total_visits' => fn ($query) => $query->where('status', 'completed')])
            ->withSum(['appointments as total_spend' => fn ($query) => $query->where('status', 'completed')], 'price')
            ->with(['appointments' => fn ($query) => $query->orderByDesc('appointment_date')->orderByDesc('start_time')->with(['staff.user', 'category'])])
            ->orderByDesc('id')
            ->get();

        $clients->each(function (User $client) use ($gradients): void {
            $totalVisits = (int) ($client->total_visits ?? 0);
            $totalSpend = (float) ($client->total_spend ?? 0);
            $loyaltyPoints = (int) floor($totalSpend / 50);

            $membershipTier = match (true) {
                $totalSpend >= 25000 => 'VIP Platinum',
                $totalSpend >= 10000 => 'Gold Member',
                $totalVisits >= 1 => 'Regular',
                default => 'New Client',
            };

            /** @var Appointment|null $latestAppointment */
            $latestAppointment = $client->appointments->first();
            $preferredStylist = $latestAppointment?->staff?->user?->name ?? 'Any Available Stylist';

            $nameParts = preg_split('/\s+/', trim($client->name)) ?: [];
            $initials = '';
            foreach (array_slice($nameParts, 0, 2) as $part) {
                $initials .= strtoupper(substr($part, 0, 1));
            }

            $year = $client->created_at?->format('Y') ?? now()->format('Y');

            $client->setAttribute('total_spent', $totalSpend);
            $client->setAttribute('total_spend', $totalSpend);
            $client->setAttribute('total_visits', $totalVisits);
            $client->setAttribute('loyalty_points', $loyaltyPoints);
            $client->setAttribute('membership_tier', $membershipTier);
            $client->setAttribute('client_code', sprintf('CL-%s-%04d', $year, $client->id));
            $client->setAttribute('raw_phone_number', $client->phone_number ?? '');
            $client->setAttribute('joined_date', $client->created_at?->format('M d, Y') ?? 'Recently');
            $client->setAttribute('email_verified', $client->email_verified_at !== null);
            $client->setAttribute('preferred_stylist', $preferredStylist);
            $client->setAttribute('avatar_initials', $initials ?: 'CL');
            $client->setAttribute('avatar_gradient', $gradients[$client->id % count($gradients)]);
            $client->setAttribute('last_visit', $latestAppointment?->appointment_date?->format('M d, Y') ?? 'No visits yet');
            $client->setAttribute(
                'latest_service_summary',
                $latestAppointment
                    ? "{$latestAppointment->service_name} ({$latestAppointment->appointment_date?->format('M d, Y')})"
                    : 'Registered in database — ready for first salon appointment.'
            );
            $client->setAttribute(
                'recent_services',
                $client->appointments->map(fn (Appointment $apt): array => [
                    'service' => $apt->service_name,
                    'date' => $apt->appointment_date?->format('M d, Y') ?? '',
                    'amount' => (float) $apt->price,
                    'stylist' => $apt->staff?->user?->name ?? 'Salon Stylist',
                    'status' => $apt->status,
                ])->values()->all()
            );
        });

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

    /**
     * Display a single client's CRM profile and appointment history.
     */
    public function show(User $client): View
    {
        abort_unless($client->role === 'client', 404);

        $client->load([
            'appointments' => fn ($query) => $query->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->with(['staff.user', 'category']),
        ]);

        $completedAppointments = $client->appointments->where('status', 'completed');
        $totalVisits = $completedAppointments->count();
        $totalSpend = (float) $completedAppointments->sum('price');
        $loyaltyPoints = (int) floor($totalSpend / 50);

        $membershipTier = match (true) {
            $totalSpend >= 25000 => 'VIP Platinum',
            $totalSpend >= 10000 => 'Gold Member',
            $totalVisits >= 1 => 'Regular',
            default => 'New Client',
        };

        return view('clients.show', compact(
            'client',
            'totalVisits',
            'totalSpend',
            'loyaltyPoints',
            'membershipTier'
        ));
    }

    /**
     * Store a newly registered client in the users table (role = 'client').
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'phone_number' => preg_replace('/[\s\-()]+/', '', (string) $request->input('phone_number')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')],
            'phone_number' => ['required', 'string', 'regex:/^(?:\+94|0)7\d{8}$/'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
        ]);

        $client = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => 'password',
            'phone_number' => $validated['phone_number'],
            'gender' => $validated['gender'],
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('clients.index')
            ->with('success', "Client {$client->name} saved to the database successfully!");
    }

    /**
     * Update an existing client record in the database.
     */
    public function update(Request $request, User $client): RedirectResponse
    {
        abort_unless($client->role === 'client', 404);

        $request->merge([
            'phone_number' => preg_replace('/[\s\-()]+/', '', (string) $request->input('phone_number')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($client->id)],
            'phone_number' => ['required', 'string', 'regex:/^(?:\+94|0)7\d{8}$/'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
        ]);

        $previousName = $client->name;

        $client->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'gender' => $validated['gender'],
        ]);

        if ($previousName !== $validated['name']) {
            Appointment::where('client_id', $client->id)->update([
                'client_name' => $validated['name'],
            ]);
        }

        return redirect()
            ->route('clients.index')
            ->with('success', "Client profile for {$client->name} updated successfully!");
    }

    /**
     * Delete a client record from the database (Main Admin / Owner only).
     */
    public function destroy(Request $request, User $client): RedirectResponse
    {
        abort_unless($request->user()?->isOwner(), 403, 'Only the main salon admin can delete client records.');
        abort_unless($client->role === 'client', 404);

        $clientName = $client->name;
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', "{$clientName} has been removed from the client database.");
    }

    /**
     * Look up an existing User by normalized Sri Lankan phone number for appointment auto-complete.
     */
    public function searchByPhone(Request $request): JsonResponse
    {
        $normalized = preg_replace('/[\s\-()]+/', '', (string) $request->query('phone', '')) ?? '';

        if (strlen($normalized) < 9) {
            return response()->json([
                'found' => false,
                'user' => null,
            ], 200);
        }

        $candidates = [$normalized];
        if (str_starts_with($normalized, '0') && strlen($normalized) === 10) {
            $candidates[] = '+94'.substr($normalized, 1);
        } elseif (str_starts_with($normalized, '+94') && strlen($normalized) === 12) {
            $candidates[] = '0'.substr($normalized, 3);
        }

        $user = User::query()
            ->whereIn('phone_number', $candidates)
            ->orderByRaw("CASE WHEN role = 'client' THEN 0 ELSE 1 END")
            ->first(['id', 'name', 'phone_number', 'gender']);

        if (! $user) {
            return response()->json([
                'found' => false,
                'user' => null,
            ], 200);
        }

        return response()->json([
            'found' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'gender' => $user->gender,
            ],
        ], 200);
    }
}
