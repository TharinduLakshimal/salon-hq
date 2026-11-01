<!-- Left 2 Columns: Today's Appointments -->
<div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-2">
        <div>
            <h2 class="text-base font-bold text-slate-900">Today's Appointments</h2>
            <p class="text-xs text-slate-500 mt-0.5">Live chronological salon feed for today</p>
        </div>
        <a href="{{ route('appointments.index') }}" wire:navigate class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 whitespace-nowrap">View All Bookings &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                    <th class="px-4 sm:px-6 py-3">Client</th>
                    <th class="px-4 sm:px-6 py-3">Service</th>
                    <th class="px-4 sm:px-6 py-3">Stylist</th>
                    <th class="px-4 sm:px-6 py-3">Time</th>
                    <th class="px-4 sm:px-6 py-3">Price</th>
                    <th class="px-4 sm:px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @forelse ($todayAppointments as $appointment)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                            <div class="font-semibold text-slate-900">{{ $appointment->display_name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $appointment->client_phone }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                            <div class="font-medium text-slate-800">{{ $appointment->service_name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $appointment->category?->name ?? 'Salon Service' }} &bull; {{ $appointment->duration_minutes }}m</div>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0 shadow-2xs" style="background: {{ $appointment->staff?->avatar_gradient ?: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)' }}">
                                    {{ $appointment->staff?->initials ?? 'ST' }}
                                </div>
                                <span class="text-slate-800 font-medium">{{ $appointment->staff?->user?->name ?? 'Unassigned' }}</span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-slate-700 font-medium">
                            {{ $appointment->formatted_start_time }}
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap font-bold text-slate-900">
                            Rs. {{ number_format($appointment->price, 0) }}
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                            @php
                                $statusClasses = match ($appointment->status) {
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'confirmed' => 'bg-emerald-100 text-emerald-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'completed' => 'bg-indigo-100 text-indigo-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                {{ $appointment->status_label }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 sm:px-6 py-12 text-center">
                            <div class="max-w-sm mx-auto flex flex-col items-center">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="text-sm font-semibold text-slate-900">No appointments scheduled for today</div>
                                <p class="text-xs text-slate-500 mt-1">Bookings scheduled for today will appear here in chronological order.</p>
                                <a href="{{ route('appointments.index', ['new' => 1]) }}" wire:navigate class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition">
                                    + Book New Appointment
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
