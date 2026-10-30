<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffWork;
use App\Models\User;
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

        $worksByClientName = StaffWork::with('staff.user')
            ->latest('id')
            ->get()
            ->groupBy(fn (StaffWork $work): string => strtolower(trim($work->client_name)));

        $gradients = [
            'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
            'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
            'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
            'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)',
        ];

        $clientUsers = User::query()
            ->where('role', 'client')
            ->orderByDesc('id')
            ->get();

        $clients = $clientUsers->map(function (User $clientUser) use ($worksByClientName, $gradients): array {
            $lookupKey = strtolower(trim($clientUser->name));
            $clientWorks = $worksByClientName->get($lookupKey, collect());

            $totalVisits = $clientWorks->count();
            $totalSpent = (float) $clientWorks->sum('price');
            $loyaltyPoints = (int) floor($totalSpent / 50);

            $membershipTier = match (true) {
                $totalSpent >= 25000 => 'VIP Platinum',
                $totalSpent >= 10000 => 'Gold Member',
                $totalVisits >= 1 => 'Regular',
                default => 'New Client',
            };

            /** @var StaffWork|null $latestWork */
            $latestWork = $clientWorks->first();
            $preferredStylist = $latestWork?->staff?->user?->name ?? 'Any Available Stylist';

            $nameParts = preg_split('/\s+/', trim($clientUser->name)) ?: [];
            $initials = '';
            foreach (array_slice($nameParts, 0, 2) as $part) {
                $initials .= strtoupper(substr($part, 0, 1));
            }

            $year = $clientUser->created_at?->format('Y') ?? now()->format('Y');

            return [
                'id' => $clientUser->id,
                'client_code' => sprintf('CL-%s-%04d', $year, $clientUser->id),
                'name' => $clientUser->name,
                'email' => $clientUser->email,
                'phone_number' => $clientUser->phone_number ?: 'Not provided',
                'raw_phone_number' => $clientUser->phone_number ?? '',
                'gender' => $clientUser->gender ?: 'female',
                'joined_date' => $clientUser->created_at?->format('M d, Y') ?? 'Recently',
                'email_verified' => $clientUser->email_verified_at !== null,
                'preferred_stylist' => $preferredStylist,
                'membership_tier' => $membershipTier,
                'loyalty_points' => $loyaltyPoints,
                'total_visits' => $totalVisits,
                'total_spent' => $totalSpent,
                'last_visit' => $latestWork?->performed_date ?? 'No visits yet',
                'latest_service_summary' => $latestWork
                    ? "{$latestWork->title} ({$latestWork->performed_date})"
                    : 'Registered in database — ready for first salon appointment.',
                'avatar_initials' => $initials ?: 'CL',
                'avatar_gradient' => $gradients[$clientUser->id % count($gradients)],
                'recent_services' => $clientWorks->map(fn (StaffWork $work): array => [
                    'service' => $work->title,
                    'date' => $work->performed_date,
                    'amount' => (float) $work->price,
                    'stylist' => $work->staff?->user?->name ?? 'Salon Stylist',
                ])->values()->all(),
            ];
        })->values()->all();

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
            StaffWork::where('client_name', $previousName)->update([
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
}
