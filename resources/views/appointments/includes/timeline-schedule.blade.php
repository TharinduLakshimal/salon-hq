<!-- Daily Chair / Time-Slot Schedule View -->
<div x-show="viewMode === 'timeline'" class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs" style="display: none;">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daily Chair Schedule</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Hourly salon slot breakdown &mdash; click any open slot to schedule a walk-in or phone booking.
            </p>
        </div>

        <button
            type="button"
            @click="openNewBookingModal()"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold transition cursor-pointer"
        >
            <span>+ Book Custom Slot</span>
        </button>
    </div>

    <div class="divide-y divide-slate-200">
        <template x-for="slot in timeSlots" :key="slot">
            <div class="p-4 sm:px-6 sm:py-4 flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-5 hover:bg-slate-50/50 transition">
                <!-- Time Label Column -->
                <div class="sm:w-28 shrink-0 flex sm:flex-col items-center sm:items-start justify-between">
                    <span class="text-xs font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg" x-text="slot"></span>
                    <span
                        class="text-[11px] font-medium text-slate-400 sm:mt-1.5"
                        x-text="appointmentsForSlot(slot).length + (appointmentsForSlot(slot).length === 1 ? ' chair active' : ' chairs')"
                    ></span>
                </div>

                <!-- Slot Cards Grid -->
                <div class="flex-1 min-w-0">
                    <div x-show="appointmentsForSlot(slot).length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3.5">
                        <template x-for="booking in appointmentsForSlot(slot)" :key="booking.id">
                            <div
                                @click="selectedBooking = booking"
                                class="border border-slate-200 rounded-xl p-3.5 bg-white hover:border-indigo-400 hover:shadow-sm transition cursor-pointer flex flex-col justify-between gap-2.5"
                            >
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <span class="text-[11px] font-bold text-indigo-600" x-text="booking.start_time + (booking.end_time ? ' - ' + booking.end_time : '')"></span>
                                        <span
                                            :class="statusBadgeClass(booking.status)"
                                            class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                            x-text="statusLabel(booking.status)"
                                        ></span>
                                    </div>
                                    <div class="font-bold text-sm text-slate-900 truncate" x-text="booking.service_name"></div>
                                    <div class="text-xs text-slate-600 mt-0.5 flex items-center gap-1">
                                        <span x-text="booking.client_name"></span>
                                        <span
                                            :class="booking.client_gender === 'female' ? 'text-pink-600' : 'text-sky-600'"
                                            class="font-bold"
                                            x-text="booking.client_gender === 'female' ? '♀' : '♂'"
                                        ></span>
                                        <span class="text-slate-400">&bull; <span x-text="formatDateLabel(booking.date)"></span></span>
                                    </div>
                                </div>

                                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div
                                            class="w-6 h-6 rounded-full text-white text-[10px] font-bold flex items-center justify-center shrink-0"
                                            :style="'background: ' + booking.stylist_gradient"
                                            x-text="booking.stylist_initials"
                                        ></div>
                                        <span class="text-xs font-medium text-slate-700 truncate" x-text="booking.stylist_name"></span>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900 shrink-0">Rs. <span x-text="formatCurrency(booking.price)"></span></span>
                                </div>
                            </div>
                        </template>

                        <!-- Quick Add Another Chair in Same Hour -->
                        <button
                            type="button"
                            @click="openNewBookingModal(slot)"
                            class="border border-dashed border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/40 rounded-xl p-3.5 text-xs font-semibold text-slate-400 hover:text-indigo-600 flex items-center justify-center gap-1.5 min-h-[104px] transition cursor-pointer"
                        >
                            <span>+ Assign Another Chair (<span x-text="slot"></span>)</span>
                        </button>
                    </div>

                    <!-- Open Slot Placeholder -->
                    <button
                        type="button"
                        x-show="appointmentsForSlot(slot).length === 0"
                        @click="openNewBookingModal(slot)"
                        class="w-full border border-dashed border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/40 rounded-xl px-4 py-3 text-left text-xs font-medium text-slate-400 hover:text-indigo-600 flex items-center justify-between transition cursor-pointer"
                    >
                        <span>All salon chairs open at <strong class="font-semibold" x-text="slot"></strong></span>
                        <span class="font-semibold text-indigo-600">+ Book Slot</span>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

