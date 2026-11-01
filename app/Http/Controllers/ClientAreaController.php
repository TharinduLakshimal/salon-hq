<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientAreaController extends Controller
{
    /**
     * Display the logged-in client's dashboard and appointment history.
     */
    public function index(Request $request): View
    {
        /** @var User $client */
        $client = $request->user();

        $client->load([
            'appointments' => fn ($query) => $query->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->with(['staff.user', 'category']),
        ]);

        $appointments = $client->appointments;

        $upcomingAppointments = $appointments->filter(function ($appointment) {
            return in_array($appointment->status, ['pending', 'confirmed', 'in_progress'], true);
        })->values();

        $pastAppointments = $appointments->filter(function ($appointment) {
            return in_array($appointment->status, ['completed', 'cancelled'], true);
        })->values();

        $completedAppointments = $appointments->where('status', 'completed');
        $totalVisits = $completedAppointments->count();
        $totalSpend = (float) $completedAppointments->sum('price');
        $loyaltyPoints = (int) floor($totalSpend / 50);

        return view('client-area.dashboard', compact(
            'client',
            'appointments',
            'upcomingAppointments',
            'pastAppointments',
            'totalVisits',
            'totalSpend',
            'loyaltyPoints'
        ));
    }
}
