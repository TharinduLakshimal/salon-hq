@section('title', 'My Appointments & Client Portal - Salon HQ')
<!DOCTYPE html>
<html lang="en">
@include('includes.head')
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased flex flex-col justify-between">

    <!-- Top Client Navigation -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                @include('includes.brand-logo', [
                    'wrapperClass' => 'w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-sm shadow-indigo-500/20',
                    'iconClass' => 'w-5 h-5',
                ])
                <div>
                    <span class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition">Salon HQ</span>
                    <span class="block text-[11px] font-medium text-slate-400">Client Portal</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('booking.index') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm shadow-indigo-600/20 transition cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Book New Appointment</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-600 text-xs font-semibold transition cursor-pointer"
                    >
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Client Dashboard Body -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-10 space-y-6 sm:space-y-8">
        @if (session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-emerald-800 font-semibold shadow-2xs">
                {{ session('success') }}
            </div>
        @endif

        <!-- Client Welcome Banner Card -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white text-2xl font-bold flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/25">
                    {{ strtoupper(substr($client->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Welcome, {{ $client->name }}!</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">Client</span>
                    </div>
                    <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-3">
                        <span>📞 {{ $client->phone_number ?: 'No phone provided' }}</span>
                        <span>&bull;</span>
                        <span>✉️ {{ $client->email }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('booking.index') }}"
                    class="w-full md:w-auto text-center px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-indigo-600/20 transition cursor-pointer"
                >
                    + Book New Appointment
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Upcoming</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $upcomingAppointments->count() }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Scheduled visits</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Completed Visits</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $totalVisits }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Past salon visits</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Loyalty Points</div>
                <div class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($loyaltyPoints) }} pts</div>
                <div class="text-xs text-amber-600/80 mt-0.5">Reward balance</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Spend</div>
                <div class="text-2xl font-bold text-emerald-600 mt-1">Rs. {{ number_format($totalSpend, 0) }}</div>
                <div class="text-xs text-emerald-600/80 mt-0.5">Lifetime salon spend</div>
            </div>
        </div>

        <!-- Section 1: Upcoming Appointments -->
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xs">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-slate-900">Upcoming Appointments</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                        {{ $upcomingAppointments->count() }}
                    </span>
                </div>
            </div>

            @if ($upcomingAppointments->isEmpty())
                <div class="p-10 text-center text-slate-500 text-xs sm:text-sm">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="font-bold text-slate-800 text-sm">No upcoming appointments scheduled</div>
                    <p class="text-slate-400 text-xs mt-1 max-w-sm mx-auto">Ready for your next haircut, treatment, or spa service?</p>
                    <div class="mt-4">
                        <a
                            href="{{ route('booking.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition"
                        >
                            + Book an Appointment
                        </a>
                    </div>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($upcomingAppointments as $appointment)
                        <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/60 transition">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex flex-col items-center justify-center shrink-0 border border-indigo-100">
                                    <span class="text-[10px] font-bold uppercase">{{ $appointment->appointment_date?->format('M') }}</span>
                                    <span class="text-base font-bold leading-none">{{ $appointment->appointment_date?->format('d') }}</span>
                                </div>

                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm sm:text-base font-bold text-slate-900">{{ $appointment->service_name }}</h3>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700">
                                            {{ $appointment->status_label }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-3">
                                        <span>🕒 {{ Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} ({{ $appointment->duration_minutes }} mins)</span>
                                        <span>&bull;</span>
                                        <span>✂️ Stylist: <strong class="text-slate-700">{{ $appointment->staff?->user?->name ?? 'Any Stylist' }}</strong></span>
                                        <span>&bull;</span>
                                        <span class="font-mono text-slate-400">{{ $appointment->booking_code }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-4 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                                <div class="text-right">
                                    <div class="text-sm sm:text-base font-bold text-slate-900">Rs. {{ number_format((float) $appointment->price, 2) }}</div>
                                    <div class="text-[11px] text-slate-400">Pay at Salon</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Section 2: Past Appointments & Service History -->
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xs">
            <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-slate-900">Past Service History</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        {{ $pastAppointments->count() }}
                    </span>
                </div>
            </div>

            @if ($pastAppointments->isEmpty())
                <div class="p-8 text-center text-slate-400 text-xs">
                    No past salon appointments recorded yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                                <th class="px-6 py-3.5">Service</th>
                                <th class="px-4 py-3.5">Date & Time</th>
                                <th class="px-4 py-3.5">Stylist</th>
                                <th class="px-4 py-3.5">Amount</th>
                                <th class="px-6 py-3.5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pastAppointments as $appointment)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $appointment->service_name }}</div>
                                        <div class="text-[11px] font-mono text-slate-400">{{ $appointment->booking_code }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-600">
                                        <div>{{ $appointment->appointment_date?->format('M d, Y') }}</div>
                                        <div class="text-[11px] text-slate-400">{{ Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-slate-700 font-medium">
                                        {{ $appointment->staff?->user?->name ?? 'Salon Stylist' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap font-bold text-slate-900">
                                        Rs. {{ number_format((float) $appointment->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $appointment->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $appointment->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        <div class="max-w-6xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Salon HQ. All rights reserved. Unisex Salon & Luxury Spa.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>

