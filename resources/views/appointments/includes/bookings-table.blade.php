<!-- Bookings List Table View -->
<div x-show="viewMode === 'list'" class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2.5">
            <h2 class="text-base font-bold text-slate-900">Appointment Register</h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                <span x-text="filteredAppointments.length"></span> bookings
            </span>
        </div>

        <div class="flex items-center gap-3 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-pink-500"></span> ♀ Ladies</span>
            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sky-500"></span> ♂ Gents</span>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="filteredAppointments.length === 0" class="p-10 sm:p-14 text-center" style="display: none;">
        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-900">No appointments match your filters</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Try clearing your search filters or book a new client appointment slot.</p>
        <div class="mt-4 flex flex-wrap items-center justify-center gap-2.5">
            <button
                type="button"
                @click="resetFilters()"
                class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 cursor-pointer"
            >
                Reset All Filters
            </button>
            <button
                type="button"
                @click="openNewBookingModal()"
                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white cursor-pointer"
            >
                + New Appointment
            </button>
        </div>
    </div>

    <!-- Responsive Data Table -->
    <div x-show="filteredAppointments.length > 0" class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                    <th class="px-4 sm:px-6 py-3.5">Booking & Client</th>
                    <th class="px-4 sm:px-5 py-3.5">Service & Dept</th>
                    <th class="px-4 sm:px-5 py-3.5">Stylist</th>
                    <th class="px-4 sm:px-5 py-3.5">Schedule</th>
                    <th class="px-4 sm:px-5 py-3.5">Price</th>
                    <th class="px-4 sm:px-5 py-3.5">Status</th>
                    <th class="px-4 sm:px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                <template x-for="booking in filteredAppointments" :key="booking.id">
                    <tr class="hover:bg-slate-50/80 transition">
                        <!-- Booking ID & Client -->
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-mono text-[11px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700" x-text="booking.booking_code"></span>
                                <span class="text-[11px] font-medium text-slate-400" x-text="booking.booking_type"></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-semibold text-slate-900" x-text="booking.client_name"></span>
                                <span
                                    :class="booking.client_gender === 'female' ? 'bg-pink-50 text-pink-700' : 'bg-sky-50 text-sky-700'"
                                    class="inline-flex items-center justify-center w-4.5 h-4.5 rounded-full text-[11px] font-bold"
                                    x-text="booking.client_gender === 'female' ? '♀' : '♂'"
                                ></span>
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5" x-text="booking.client_phone"></div>
                        </td>

                        <!-- Service & Category -->
                        <td class="px-4 sm:px-5 py-4">
                            <div class="font-semibold text-slate-800 max-w-[240px] truncate" x-text="booking.service_name" :title="booking.service_name"></div>
                            <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-medium" x-text="booking.category"></span>
                                <span>&bull; <span x-text="booking.duration_minutes"></span> mins</span>
                            </div>
                        </td>

                        <!-- Stylist -->
                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-full text-white text-xs font-bold flex items-center justify-center shrink-0 shadow-2xs"
                                    :style="'background: ' + booking.stylist_gradient"
                                    x-text="booking.stylist_initials"
                                ></div>
                                <span class="font-medium text-slate-800" x-text="booking.stylist_name"></span>
                            </div>
                        </td>

                        <!-- Date & Time -->
                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <div class="text-xs font-bold uppercase tracking-wide text-indigo-600" x-text="formatDateLabel(booking.date)"></div>
                            <div class="text-sm font-medium text-slate-800 mt-0.5">
                                <span x-text="booking.start_time"></span>
                                <span class="text-slate-400 text-xs" x-show="booking.end_time">&ndash; <span x-text="booking.end_time"></span></span>
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap font-bold text-slate-900">
                            Rs. <span x-text="formatCurrency(booking.price)"></span>
                        </td>

                        <!-- Status Pill -->
                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <span
                                :class="statusBadgeClass(booking.status)"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"
                            >
                                <span
                                    x-show="booking.status === 'in_progress'"
                                    class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"
                                ></span>
                                <span x-text="statusLabel(booking.status)"></span>
                            </span>
                        </td>

                        <!-- Quick Actions -->
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button
                                    type="button"
                                    x-show="booking.status === 'pending'"
                                    @click="updateStatus(booking.id, 'confirmed')"
                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white text-xs font-semibold transition cursor-pointer"
                                >
                                    Confirm
                                </button>

                                <button
                                    type="button"
                                    x-show="booking.status === 'confirmed'"
                                    @click="updateStatus(booking.id, 'in_progress')"
                                    class="px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-semibold transition cursor-pointer"
                                >
                                    Start Chair
                                </button>

                                <button
                                    type="button"
                                    x-show="booking.status === 'in_progress'"
                                    @click="updateStatus(booking.id, 'completed')"
                                    class="px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold transition cursor-pointer"
                                >
                                    Complete
                                </button>

                                <button
                                    type="button"
                                    @click="openEditBookingModal(booking)"
                                    title="Edit appointment"
                                    class="px-2.5 py-1.5 rounded-lg border border-indigo-200 bg-indigo-50/60 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold transition cursor-pointer"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    @click="selectedBooking = booking"
                                    title="View appointment details"
                                    class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold transition cursor-pointer"
                                >
                                    Details
                                </button>

                                <button
                                    type="button"
                                    x-show="booking.status !== 'completed' && booking.status !== 'cancelled'"
                                    @click="updateStatus(booking.id, 'cancelled')"
                                    title="Cancel booking"
                                    class="w-7.5 h-7.5 rounded-lg border border-red-200 bg-red-50/60 hover:bg-red-600 text-red-600 hover:text-white text-xs font-bold flex items-center justify-center transition cursor-pointer"
                                >
                                    ✕
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

