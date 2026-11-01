<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dynamic owner dashboard overview backed by real MySQL records.
     */
    public function index(Request $request): View
    {
        $today = now()->toDateString();

        $todayAppointmentsQuery = Appointment::with(['client', 'staff.user', 'category'])
            ->whereDate('appointment_date', $today);

        // Fetch today's appointments ordered by start_time for the daily schedule feed
        $todayAppointments = (clone $todayAppointmentsQuery)
            ->orderBy('start_time')
            ->get();

        // Calculate today's appointment counts and expected revenue
        $todayAppointmentsCount = $todayAppointments->count();

        $todayExpectedRevenue = (float) (clone $todayAppointmentsQuery)
            ->where('status', '!=', 'cancelled')
            ->sum('price');

        $todayCompletedAppointmentsCount = $todayAppointments
            ->where('status', 'completed')
            ->count();

        // Fetch all Staff profiles with their related User to display live availability
        $staffMembers = Staff::with(['user', 'category'])
            ->orderBy('id')
            ->get();

        $availableStaffCount = $staffMembers
            ->where('availability_status', 'available')
            ->count();

        $activeClientsCount = User::where('role', 'client')->count();

        return view('dashboard', compact(
            'today',
            'todayAppointments',
            'todayAppointmentsCount',
            'todayExpectedRevenue',
            'todayCompletedAppointmentsCount',
            'staffMembers',
            'availableStaffCount',
            'activeClientsCount'
        ));
    }
}
