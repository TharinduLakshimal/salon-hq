<!-- New Appointment Modal -->
<div
    x-show="showBookingModal"
    x-transition.opacity
    @click.self="showBookingModal = false"
    class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
    style="display: none;"
>
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Book New Appointment</h2>
                <p class="text-xs text-slate-500 mt-0.5">Schedule a walk-in, phone desk, or online salon session.</p>
            </div>
            <button
                type="button"
                @click="showBookingModal = false"
                aria-label="Close modal"
                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer"
            >
                ✕
            </button>
        </div>

        <form @submit.prevent="submitNewBooking()" class="flex flex-col overflow-hidden">
            <div class="p-5 sm:p-6 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Client Full Name *</label>
                    <input
                        type="text"
                        x-model="form.client_name"
                        required
                        placeholder="e.g. Dinithi Rajapaksa"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Mobile Number *</label>
                    <input
                        type="text"
                        x-model="form.client_phone"
                        required
                        placeholder="e.g. 077 512 9080"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Client Gender *</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" x-model="form.client_gender" value="female" class="peer sr-only">
                            <div class="rounded-xl border-2 border-slate-200 p-2.5 text-center peer-checked:border-pink-500 peer-checked:bg-pink-50 peer-checked:text-pink-700 transition">
                                <div class="text-xs font-bold">♀ Female Client</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Ladies' Hair, Bridal & Nails</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="form.client_gender" value="male" class="peer sr-only">
                            <div class="rounded-xl border-2 border-slate-200 p-2.5 text-center peer-checked:border-sky-500 peer-checked:bg-sky-50 peer-checked:text-sky-700 transition">
                                <div class="text-xs font-bold">♂ Male Client</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Gents' Barber, Grooming & Skin</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Select Salon Service *</label>
                    <select
                        x-model="form.service_name"
                        @change="onServiceChange()"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                        <template x-for="service in services" :key="service.name">
                            <option :value="service.name" x-text="service.name + ' — Rs. ' + formatCurrency(service.price) + ' (' + service.duration + ' mins)'"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Assigned Stylist / Barber *</label>
                    <select
                        x-model="form.stylist_name"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                        <template x-for="stylist in stylists" :key="stylist.name">
                            <option :value="stylist.name" x-text="stylist.name + ' (' + stylist.category + ')'"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Booking Source *</label>
                    <select
                        x-model="form.booking_type"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                        <option value="Walk-In">Walk-In Desk</option>
                        <option value="Phone Desk">Phone Call</option>
                        <option value="Online Booking">Online Booking</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Appointment Date *</label>
                    <input
                        type="date"
                        x-model="form.date"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Preferred Time Slot *</label>
                    <select
                        x-model="form.start_time"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                        <option value="09:00 AM">09:00 AM</option>
                        <option value="09:30 AM">09:30 AM</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="10:30 AM">10:30 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="11:30 AM">11:30 AM</option>
                        <option value="12:00 PM">12:00 PM</option>
                        <option value="01:00 PM">01:00 PM</option>
                        <option value="01:30 PM">01:30 PM</option>
                        <option value="02:00 PM">02:00 PM</option>
                        <option value="03:00 PM">03:00 PM</option>
                        <option value="03:30 PM">03:30 PM</option>
                        <option value="04:00 PM">04:00 PM</option>
                        <option value="04:30 PM">04:30 PM</option>
                        <option value="05:00 PM">05:00 PM</option>
                    </select>
                </div>

                <div class="sm:col-span-2 rounded-xl bg-indigo-50/70 border border-indigo-100 px-4 py-3 flex flex-wrap items-center justify-between gap-2">
                    <div class="text-xs text-slate-600">
                        <span class="font-semibold text-slate-800" x-text="form.category"></span>
                        <span>&bull; Est. Duration: <strong x-text="form.duration_minutes + ' mins'"></strong></span>
                    </div>
                    <div class="text-sm font-bold text-indigo-700">
                        Total: Rs. <span x-text="formatCurrency(form.price)"></span>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Consultation / Styling Notes</label>
                    <textarea
                        x-model="form.notes"
                        rows="2"
                        placeholder="Allergies, hair length, bridal jewelry preferences, or skin sensitivity..."
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    ></textarea>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="showBookingModal = false"
                    class="px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-sm shadow-indigo-600/20 cursor-pointer"
                >
                    Confirm Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Appointment Details Quick-Inspect Modal -->
<div
    x-show="selectedBooking !== null"
    x-transition.opacity
    @click.self="selectedBooking = null"
    class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
    style="display: none;"
>
    <template x-if="selectedBooking">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800" x-text="selectedBooking.booking_code"></span>
                    <span
                        :class="statusBadgeClass(selectedBooking.status)"
                        class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                        x-text="statusLabel(selectedBooking.status)"
                    ></span>
                </div>
                <button
                    type="button"
                    @click="selectedBooking = null"
                    aria-label="Close details"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer"
                >
                    ✕
                </button>
            </div>

            <div class="p-5 sm:p-6 space-y-4 text-sm">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Client Details</div>
                    <div class="font-bold text-base text-slate-900 mt-0.5 flex items-center gap-1.5">
                        <span x-text="selectedBooking.client_name"></span>
                        <span
                            :class="selectedBooking.client_gender === 'female' ? 'text-pink-600' : 'text-sky-600'"
                            x-text="selectedBooking.client_gender === 'female' ? '♀' : '♂'"
                        ></span>
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">
                        <span x-text="selectedBooking.client_phone"></span> &bull; <span x-text="selectedBooking.booking_type"></span>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3.5 space-y-2">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-semibold text-slate-900" x-text="selectedBooking.service_name"></div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                <span x-text="selectedBooking.category"></span> &bull; <span x-text="selectedBooking.duration_minutes"></span> mins
                            </div>
                        </div>
                        <div class="font-bold text-indigo-700 shrink-0">
                            Rs. <span x-text="formatCurrency(selectedBooking.price)"></span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-600">
                        <span>Stylist: <strong class="text-slate-900" x-text="selectedBooking.stylist_name"></strong></span>
                        <span><strong x-text="formatDateLabel(selectedBooking.date)"></strong>, <span x-text="selectedBooking.start_time"></span></span>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Session Notes</div>
                    <p class="text-xs text-slate-600 bg-slate-50 rounded-xl p-3 border border-slate-100" x-text="selectedBooking.notes || 'No special notes recorded for this booking.'"></p>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Update Booking Status</div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button
                            type="button"
                            @click="updateStatus(selectedBooking.id, 'confirmed')"
                            :class="selectedBooking.status === 'confirmed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
                            class="px-2.5 py-2 rounded-xl text-xs font-semibold transition cursor-pointer"
                        >
                            Confirmed
                        </button>
                        <button
                            type="button"
                            @click="updateStatus(selectedBooking.id, 'in_progress')"
                            :class="selectedBooking.status === 'in_progress' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100'"
                            class="px-2.5 py-2 rounded-xl text-xs font-semibold transition cursor-pointer"
                        >
                            In Chair
                        </button>
                        <button
                            type="button"
                            @click="updateStatus(selectedBooking.id, 'completed')"
                            :class="selectedBooking.status === 'completed' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100'"
                            class="px-2.5 py-2 rounded-xl text-xs font-semibold transition cursor-pointer"
                        >
                            Completed
                        </button>
                        <button
                            type="button"
                            @click="updateStatus(selectedBooking.id, 'cancelled')"
                            :class="selectedBooking.status === 'cancelled' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100'"
                            class="px-2.5 py-2 rounded-xl text-xs font-semibold transition cursor-pointer"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

