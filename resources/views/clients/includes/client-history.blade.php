<!-- Appointment History & Service Records Component -->
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
    <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <h2 class="text-base font-bold text-slate-900">Appointment & Service History</h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                {{ $client->appointments->count() }} records
            </span>
        </div>
        <a
            href="{{ route('appointments.index', ['new' => 1, 'client_id' => $client->id]) }}"
            wire:navigate
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-2xs transition"
        >
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Book New Appointment</span>
        </a>
    </div>

    @if ($client->appointments->isEmpty())
        <!-- Graceful Empty State -->
        <div class="p-10 sm:p-14 text-center">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-900">No appointments recorded yet</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                {{ $client->name }} does not have any salon visits or appointments in the database.
            </p>
            <div class="mt-4">
                <a
                    href="{{ route('appointments.index', ['new' => 1, 'client_id' => $client->id]) }}"
                    wire:navigate
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-2xs transition"
                >
                    + Book First Appointment
                </a>
            </div>
        </div>
    @else
        <!-- Horizontally Scrollable Appointments Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                        <th class="px-5 sm:px-6 py-3.5">Service & Department</th>
                        <th class="px-4 sm:px-5 py-3.5">Date & Time</th>
                        <th class="px-4 sm:px-5 py-3.5">Assigned Stylist</th>
                        <th class="px-4 sm:px-5 py-3.5">Booking Type</th>
                        <th class="px-4 sm:px-5 py-3.5">Price</th>
                        <th class="px-5 sm:px-6 py-3.5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @foreach ($client->appointments as $appointment)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 sm:px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $appointment->service_name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                    <span>{{ $appointment->category?->name ?? 'Salon Service' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $appointment->duration_minutes }} mins</span>
                                </div>
                            </td>

                            <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-800">
                                    {{ $appointment->appointment_date?->format('M d, Y') ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 font-mono">
                                    {{ $appointment->start_time }} &ndash; {{ $appointment->end_time }}
                                </div>
                            </td>

                            <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ $appointment->staff?->initials ?? 'ST' }}
                                    </div>
                                    <div class="font-medium text-slate-800">
                                        {{ $appointment->staff?->user?->name ?? 'Any Stylist' }}
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 sm:px-5 py-4 whitespace-nowrap text-xs text-slate-600">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium">
                                    {{ $appointment->booking_type_label }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-5 py-4 whitespace-nowrap font-bold text-slate-900">
                                Rs. {{ number_format((float) $appointment->price, 2) }}
                            </td>

                            <td class="px-5 sm:px-6 py-4 whitespace-nowrap text-right">
                                @php
                                    $statusClasses = match ($appointment->status) {
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'in_progress' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">
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

