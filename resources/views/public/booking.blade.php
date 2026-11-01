@section('title', 'Book Salon Appointment Online')
<!DOCTYPE html>
<html lang="en">
@include('includes.head')
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased flex flex-col justify-between">

    <!-- Client-Facing Top Navigation -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                @include('includes.brand-logo', [
                    'wrapperClass' => 'w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-sm shadow-indigo-500/20',
                    'iconClass' => 'w-5 h-5',
                ])
                <div>
                    <span class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition">Salon HQ</span>
                    <span class="block text-[11px] font-medium text-slate-400">Online Booking Portal</span>
                </div>
            </a>

            <div class="flex items-center gap-3 text-xs font-semibold">
                @auth
                    @if (auth()->user()->isClient())
                        <a href="{{ route('client.dashboard') }}" class="text-slate-600 hover:text-indigo-600 transition">
                            My Account
                        </a>
                    @elseif (auth()->user()->isWorker())
                        <a href="{{ route('appointments.index') }}" class="text-slate-600 hover:text-indigo-600 transition">
                            My Account
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-indigo-600 transition">
                            My Account
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-indigo-600 transition">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Booking Experience Container -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-10">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs sm:text-sm text-rose-700 font-medium">
                <div class="font-bold mb-1">Please fix the following issues:</div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Booking Data Configuration Script -->
        <script>
            window.__BOOKING_CONFIG__ = {
                selectedCategory: parseInt('{{ $categories->first()?->id ?? 0 }}'),
                categories: {{ Js::from($categories) }},
                services: {{ Js::from($services) }},
                allStaff: {{ Js::from($allStaff) }},
                bookedAppointments: {{ Js::from($bookedAppointments ?? []) }},
                today: '{{ now()->format('Y-m-d') }}',
                user: {{ Js::from(auth()->user() ? [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                    'phone_number' => auth()->user()->phone_number,
                    'gender' => auth()->user()->gender,
                    'email' => auth()->user()->email,
                ] : null) }},
                oldInput: {{ Js::from([
                    'client_name' => old('client_name'),
                    'client_phone' => old('client_phone'),
                    'client_gender' => old('client_gender'),
                    'client_email' => old('client_email'),
                    'notes' => old('notes'),
                ]) }},
            };

            window.publicBookingForm = function (config) {
                return {
                    step: 1,
                    selectedCategory: parseInt(config.selectedCategory) || (config.categories.length > 0 ? parseInt(config.categories[0].id) : null),
                    categories: config.categories || [],
                    services: config.services || [],
                    allStaff: config.allStaff || [],
                    bookedAppointments: config.bookedAppointments || [],

                    // Client Gender Selection & Filter: 'female' (Ladies), 'male' (Gents), or 'all'
                    genderFilter: config.oldInput.client_gender || config.user?.gender || 'female',

                    selectedService: null,
                    selectedStaffId: config.allStaff.length > 0 ? config.allStaff[0].id : null,
                    selectedStaff: config.allStaff.length > 0 ? config.allStaff[0] : null,
                    selectedDate: config.today,
                    selectedTime: '10:00 AM',

                    clientName: config.oldInput.client_name || config.user?.name || '',
                    clientPhone: config.oldInput.client_phone || config.user?.phone_number || '',
                    clientGender: config.oldInput.client_gender || config.user?.gender || 'female',
                    clientEmail: config.oldInput.client_email || config.user?.email || '',
                    notes: config.oldInput.notes || '',

                    timeSlots: [
                        '09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM',
                        '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM',
                        '05:00 PM', '06:00 PM', '07:00 PM'
                    ],

                    init() {
                        // If current category has no services for initial gender filter, pick first valid category
                        if (!this.categoryHasServices(this.selectedCategory)) {
                            const firstValid = this.categories.find(c => this.categoryHasServices(c.id));
                            if (firstValid) {
                                this.selectedCategory = firstValid.id;
                            }
                        }
                        this.ensureValidTimeSlot();
                    },

                    serviceMatchesGender(service, gender) {
                        const filter = gender || this.genderFilter;
                        if (!filter || filter === 'all') return true;
                        const target = (service.target_gender || 'both').toLowerCase();
                        if (target === 'both') return true;
                        if (filter === 'female') return target === 'ladies' || target === 'female';
                        if (filter === 'male') return target === 'gents' || target === 'male';
                        return true;
                    },

                    categoryServiceCount(categoryId) {
                        return this.services.filter(s => s.category_id == categoryId && this.serviceMatchesGender(s, this.genderFilter)).length;
                    },

                    categoryHasServices(categoryId) {
                        return this.categoryServiceCount(categoryId) > 0;
                    },

                    setGenderFilter(gender) {
                        this.genderFilter = gender;
                        if (gender === 'female' || gender === 'male') {
                            this.clientGender = gender;
                        }

                        // Deselect if selected service does not match the new gender filter
                        if (this.selectedService && !this.serviceMatchesGender(this.selectedService, gender)) {
                            this.selectedService = null;
                        }

                        // Auto-switch to the first category that has services for this gender if needed
                        if (!this.categoryHasServices(this.selectedCategory)) {
                            const firstValid = this.categories.find(c => this.categoryHasServices(c.id));
                            if (firstValid) {
                                this.selectedCategory = firstValid.id;
                            }
                        }
                    },

                    get availableStaff() {
                        let pool = this.allStaff;
                        if (this.selectedService) {
                            const catStaff = pool.filter(s => parseInt(s.category_id) === parseInt(this.selectedService.category_id));
                            if (catStaff.length > 0) {
                                pool = catStaff;
                            }
                        }
                        if (this.clientGender === 'male' || this.clientGender === 'female') {
                            const genderStaff = pool.filter(s => {
                                const serves = s.serves_gender ? s.serves_gender.toLowerCase() : 'both';
                                return serves === 'both' || serves === this.clientGender;
                            });
                            if (genderStaff.length > 0) {
                                return genderStaff;
                            }
                        }
                        return pool;
                    },

                    timeToMinutes(timeStr) {
                        if (!timeStr) return 0;
                        const match = timeStr.trim().match(/^(\d{1,2}):(\d{2})(?::\d{2})?\s*(AM|PM)?$/i);
                        if (!match) return 0;
                        let hours = parseInt(match[1]);
                        const minutes = parseInt(match[2]);
                        const modifier = match[3] ? match[3].toUpperCase() : null;
                        if (modifier === 'PM' && hours < 12) hours += 12;
                        if (modifier === 'AM' && hours === 12) hours = 0;
                        return hours * 60 + minutes;
                    },

                    isSlotBooked(slot) {
                        if (!this.selectedStaffId || !this.selectedDate) return false;
                        const slotMinutes = this.timeToMinutes(slot);
                        const duration = this.selectedService ? (parseInt(this.selectedService.duration_minutes) || 45) : 45;
                        const slotEnd = slotMinutes + duration;

                        // Check booked appointments strictly for the selected stylist in the database
                        return this.bookedAppointments.some(appt => {
                            if (parseInt(appt.staff_id) !== parseInt(this.selectedStaffId)) return false;
                            if (appt.date !== this.selectedDate) return false;

                            const apptStart = this.timeToMinutes(appt.start_time || appt.start_raw);
                            const apptEnd = this.timeToMinutes(appt.end_time || appt.end_raw);

                            // Exact slot start match
                            if (apptStart === slotMinutes) return true;
                            // Interval overlap: slot overlaps with an existing appointment
                            if (apptEnd > apptStart && slotMinutes < apptEnd && slotEnd > apptStart) return true;

                            return false;
                        });
                    },

                    availableSlotsCount() {
                        return this.timeSlots.filter(slot => !this.isSlotBooked(slot)).length;
                    },

                    ensureValidTimeSlot() {
                        if (this.selectedTime && this.isSlotBooked(this.selectedTime)) {
                            const firstAvail = this.timeSlots.find(slot => !this.isSlotBooked(slot));
                            this.selectedTime = firstAvail || '';
                        } else if (!this.selectedTime) {
                            const firstAvail = this.timeSlots.find(slot => !this.isSlotBooked(slot));
                            if (firstAvail) {
                                this.selectedTime = firstAvail;
                            }
                        }
                    },

                    async fetchFreshAvailability() {
                        if (!this.selectedStaffId || !this.selectedDate) return;
                        try {
                            const res = await fetch(`/book/availability?staff_id=${this.selectedStaffId}&date=${this.selectedDate}`);
                            if (res.ok) {
                                const data = await res.json();
                                if (Array.isArray(data.booked_slots)) {
                                    this.bookedAppointments = this.bookedAppointments.filter(
                                        a => !(parseInt(a.staff_id) === parseInt(this.selectedStaffId) && a.date === this.selectedDate)
                                    );
                                    data.booked_slots.forEach(item => {
                                        this.bookedAppointments.push({
                                            staff_id: parseInt(this.selectedStaffId),
                                            date: this.selectedDate,
                                            start_time: item.start_time,
                                            end_time: item.end_time,
                                            start_raw: item.start_raw,
                                            end_raw: item.end_raw,
                                        });
                                    });
                                    this.ensureValidTimeSlot();
                                }
                            }
                        } catch (e) {
                            // Fallback to preloaded data
                        }
                    },

                    onDateChange() {
                        this.ensureValidTimeSlot();
                        this.fetchFreshAvailability();
                    },

                    selectService(service) {
                        this.selectedService = service;
                        const catStaff = this.allStaff.filter(s => parseInt(s.category_id) === parseInt(service.category_id));
                        if (catStaff.length > 0) {
                            this.selectedStaffId = catStaff[0].id;
                            this.selectedStaff = catStaff[0];
                        } else if (this.allStaff.length > 0) {
                            this.selectedStaffId = this.allStaff[0].id;
                            this.selectedStaff = this.allStaff[0];
                        }
                        this.ensureValidTimeSlot();
                        this.fetchFreshAvailability();
                    },

                    selectStaff(staff) {
                        this.selectedStaffId = staff.id;
                        this.selectedStaff = staff;
                        this.ensureValidTimeSlot();
                        this.fetchFreshAvailability();
                    },

                    formatPrice(price) {
                        return Number(price || 0).toLocaleString('en-US', { minimumFractionDigits: 2 });
                    },

                    canGoToStep2() {
                        return this.selectedService !== null;
                    },

                    canGoToStep3() {
                        return this.selectedStaffId !== null &&
                               this.selectedDate !== '' &&
                               this.selectedTime !== '' &&
                               !this.isSlotBooked(this.selectedTime);
                    },

                    canGoToStep4() {
                        return this.clientName.trim() !== '' && this.clientPhone.trim() !== '' && this.clientGender !== '';
                    },

                    goToStep(targetStep) {
                        if (targetStep < this.step) {
                            this.step = targetStep;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else if (targetStep === 2 && this.canGoToStep2()) {
                            this.step = 2;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else if (targetStep === 3 && this.canGoToStep2() && this.canGoToStep3()) {
                            this.step = 3;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else if (targetStep === 4 && this.canGoToStep2() && this.canGoToStep3() && this.canGoToStep4()) {
                            this.step = 4;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    },

                    nextStep() {
                        if (this.step === 1 && this.canGoToStep2()) {
                            this.step = 2;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else if (this.step === 2 && this.canGoToStep3()) {
                            this.step = 3;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else if (this.step === 3 && this.canGoToStep4()) {
                            this.step = 4;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    },

                    prevStep() {
                        if (this.step > 1) {
                            this.step--;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    }
                };
            };
        </script>

        <div
            x-data="window.publicBookingForm(window.__BOOKING_CONFIG__)"
            class="space-y-6"
        >
            <!-- Multi-Step Progress Tracker Bar -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                <div class="grid grid-cols-4 gap-2 text-center text-xs font-semibold">
                    <button
                        type="button"
                        @click="goToStep(1)"
                        :class="step >= 1 ? 'text-indigo-600' : 'text-slate-400'"
                        class="flex flex-col items-center gap-1.5 transition cursor-pointer"
                    >
                        <div
                            :class="step >= 1 ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-400'"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                        >1</div>
                        <span class="truncate">Select Service</span>
                    </button>

                    <button
                        type="button"
                        @click="goToStep(2)"
                        :disabled="!canGoToStep2()"
                        :class="step >= 2 ? 'text-indigo-600' : (canGoToStep2() ? 'text-slate-600' : 'text-slate-300 opacity-60 cursor-not-allowed')"
                        class="flex flex-col items-center gap-1.5 transition cursor-pointer"
                    >
                        <div
                            :class="step >= 2 ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-400'"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                        >2</div>
                        <span class="truncate">Stylist & Time</span>
                    </button>

                    <button
                        type="button"
                        @click="goToStep(3)"
                        :disabled="!canGoToStep3()"
                        :class="step >= 3 ? 'text-indigo-600' : (canGoToStep3() ? 'text-slate-600' : 'text-slate-300 opacity-60 cursor-not-allowed')"
                        class="flex flex-col items-center gap-1.5 transition cursor-pointer"
                    >
                        <div
                            :class="step >= 3 ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-400'"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                        >3</div>
                        <span class="truncate">Your Details</span>
                    </button>

                    <button
                        type="button"
                        @click="goToStep(4)"
                        :disabled="!canGoToStep4()"
                        :class="step >= 4 ? 'text-indigo-600' : (canGoToStep4() ? 'text-slate-600' : 'text-slate-300 opacity-60 cursor-not-allowed')"
                        class="flex flex-col items-center gap-1.5 transition cursor-pointer"
                    >
                        <div
                            :class="step >= 4 ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-400'"
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                        >4</div>
                        <span class="truncate">Confirm</span>
                    </button>
                </div>

                <!-- Animated Progress Fill Strip -->
                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div
                        class="bg-indigo-600 h-full transition-all duration-300"
                        :style="'width: ' + ((step - 1) / 3 * 100) + '%'"
                    ></div>
                </div>
            </div>

            <!-- STEP 1: SERVICE SELECTION -->
            <div x-show="step === 1" x-transition.opacity class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 shadow-2xs">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Choose a Salon Service</h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Explore our professional hair, skin, bridal, and nail treatments.</p>
                        </div>
                        <span class="text-[11px] font-mono text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">
                            <span x-text="categoryServiceCount(selectedCategory)"></span> of <span x-text="services.filter(s => serviceMatchesGender(s, genderFilter)).length"></span> available
                        </span>
                    </div>

                    <!-- 1. Client Gender Selection (Ladies / Gents / All) -->
                    <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-slate-50 via-indigo-50/30 to-purple-50/20 border border-slate-200/90">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>Who is this appointment for?</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Filter haircuts and treatments tailored for men or women.</p>
                            </div>

                            <div class="grid grid-cols-3 sm:flex items-center gap-2">
                                <!-- Ladies Button -->
                                <button
                                    type="button"
                                    @click="setGenderFilter('female')"
                                    :class="genderFilter === 'female'
                                        ? 'bg-pink-600 text-white shadow-sm shadow-pink-600/30 ring-2 ring-pink-500/25 font-bold'
                                        : 'bg-white text-slate-700 border border-slate-200 hover:border-pink-300 hover:bg-pink-50/50 font-semibold'"
                                    class="px-3.5 py-2 rounded-xl text-xs transition flex items-center justify-center gap-1.5 cursor-pointer"
                                >
                                    <span class="text-sm">♀</span>
                                    <span>Ladies</span>
                                </button>

                                <!-- Gents Button -->
                                <button
                                    type="button"
                                    @click="setGenderFilter('male')"
                                    :class="genderFilter === 'male'
                                        ? 'bg-sky-600 text-white shadow-sm shadow-sky-600/30 ring-2 ring-sky-500/25 font-bold'
                                        : 'bg-white text-slate-700 border border-slate-200 hover:border-sky-300 hover:bg-sky-50/50 font-semibold'"
                                    class="px-3.5 py-2 rounded-xl text-xs transition flex items-center justify-center gap-1.5 cursor-pointer"
                                >
                                    <span class="text-sm">♂</span>
                                    <span>Gents</span>
                                </button>

                                <!-- All Services Button -->
                                <button
                                    type="button"
                                    @click="setGenderFilter('all')"
                                    :class="genderFilter === 'all'
                                        ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30 ring-2 ring-indigo-500/25 font-bold'
                                        : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300 hover:bg-slate-100 font-semibold'"
                                    class="px-3 py-2 rounded-xl text-xs transition flex items-center justify-center gap-1 cursor-pointer"
                                >
                                    <span>All</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection Tabs (Rendered via Blade from $categories) -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 border-b border-slate-100">
                        @foreach ($categories as $cat)
                            <button
                                type="button"
                                @click="selectedCategory = {{ $cat->id }}"
                                x-show="categoryServiceCount({{ $cat->id }}) > 0"
                                :class="selectedCategory == {{ $cat->id }}
                                    ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/20'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer flex items-center gap-1.5"
                            >
                                <span>{{ $cat->name }}</span>
                                <span
                                    :class="selectedCategory == {{ $cat->id }} ? 'bg-white/20 text-white' : 'bg-slate-200/80 text-slate-500'"
                                    class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold"
                                    x-text="categoryServiceCount({{ $cat->id }})"
                                ></span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Services Grid Filtered by Category and Gender -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="service in services.filter(s => s.category_id == selectedCategory && serviceMatchesGender(s, genderFilter))" :key="service.id">
                            <div
                                @click="selectService(service)"
                                :class="selectedService && selectedService.id === service.id
                                    ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/20'
                                    : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/70'"
                                class="rounded-2xl border-2 p-4 flex flex-col justify-between gap-3 transition cursor-pointer"
                            >
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-sm font-bold text-slate-900" x-text="service.name"></h3>
                                        <span
                                            :class="selectedService && selectedService.id === service.id
                                                ? 'bg-indigo-600 text-white'
                                                : 'border-2 border-slate-300 text-transparent'"
                                            class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold shrink-0 transition"
                                        >✓</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-slate-500 mt-1.5">
                                        <span class="inline-flex items-center gap-1 font-medium">
                                            ⏱ <span x-text="service.duration_minutes"></span> mins
                                        </span>
                                        <span>&bull;</span>
                                        <span
                                            :class="service.target_gender === 'ladies' ? 'text-pink-600 font-semibold' : (service.target_gender === 'gents' ? 'text-sky-600 font-semibold' : 'text-slate-500 font-medium')"
                                            class="capitalize"
                                            x-text="service.target_gender === 'ladies' ? 'Ladies' : (service.target_gender === 'gents' ? 'Gents' : 'Unisex')"
                                        ></span>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs text-slate-400 font-semibold uppercase">Price</span>
                                    <span class="text-sm sm:text-base font-bold text-slate-900">
                                        Rs. <span x-text="formatPrice(service.price)"></span>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty Category Notice -->
                    <div
                        x-show="services.filter(s => s.category_id == selectedCategory && serviceMatchesGender(s, genderFilter)).length === 0"
                        x-cloak
                        style="display: none;"
                        class="p-8 text-center text-slate-400 text-xs"
                    >
                        No services or haircuts found under this department for the selected client.
                    </div>
                </div>

                <!-- Step 1 Bottom Action Strip -->
                <div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-3 shadow-2xs">
                    <div class="text-xs text-slate-600">
                        <template x-if="selectedService">
                            <div>
                                Selected: <strong class="text-slate-900" x-text="selectedService.name"></strong>
                                (<span class="text-emerald-700 font-bold">Rs. <span x-text="formatPrice(selectedService.price)"></span></span>)
                            </div>
                        </template>
                        <template x-if="!selectedService">
                            <span class="text-slate-400">Please choose a salon service from the list above to proceed.</span>
                        </template>
                    </div>

                    <button
                        type="button"
                        @click="nextStep()"
                        :disabled="!canGoToStep2()"
                        :class="canGoToStep2()
                            ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-600/20 cursor-pointer'
                            : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition inline-flex items-center gap-1.5"
                    >
                        <span>Continue to Stylist & Time</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </div>

            <!-- STEP 2: STYLIST & TIME SELECTION -->
            <div x-show="step === 2" x-transition.opacity class="space-y-6" style="display: none;">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 shadow-2xs space-y-7">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Choose Stylist & Appointment Time</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Select your preferred salon professional and preferred booking slot.</p>
                    </div>

                    <!-- Stylist Selector -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-3">1. Select Stylist</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <template x-for="staff in availableStaff" :key="staff.id">
                                <div
                                    @click="selectStaff(staff)"
                                    :class="selectedStaffId === staff.id
                                        ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/20'
                                        : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/70'"
                                    class="rounded-xl border-2 p-3.5 flex items-center justify-between gap-3 transition cursor-pointer"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div
                                            class="w-10 h-10 rounded-xl text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs"
                                            :style="'background: ' + (staff.avatar_gradient || 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)')"
                                            x-text="staff.user ? staff.user.name.substring(0, 2).toUpperCase() : 'ST'"
                                        ></div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-slate-900 truncate" x-text="staff.user ? staff.user.name : 'Salon Stylist'"></div>
                                            <div class="text-[11px] font-medium text-indigo-600 truncate" x-text="staff.job_title"></div>
                                        </div>
                                    </div>
                                    <span
                                        :class="selectedStaffId === staff.id
                                            ? 'bg-indigo-600 text-white'
                                            : 'border-2 border-slate-300 text-transparent'"
                                        class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold shrink-0 transition"
                                    >✓</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Date & Time Pickers Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                        <!-- Date Picker -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">2. Appointment Date *</label>
                            <input
                                type="date"
                                x-model="selectedDate"
                                @change="onDateChange()"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Available today onwards (9:00 AM – 8:00 PM).</p>
                        </div>

                        <!-- Dynamic Time Slot Picker (Filtered by Real Stylist Availability) -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">3. Time Slot *</label>
                                <span
                                    class="text-[11px] font-semibold"
                                    :class="availableSlotsCount() > 0 ? 'text-emerald-600' : 'text-rose-600'"
                                >
                                    <span x-text="availableSlotsCount()"></span> of <span x-text="timeSlots.length"></span> available
                                </span>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                                <template x-for="slot in timeSlots" :key="slot">
                                    <button
                                        type="button"
                                        @click="!isSlotBooked(slot) && (selectedTime = slot)"
                                        :disabled="isSlotBooked(slot)"
                                        :class="isSlotBooked(slot)
                                            ? 'bg-slate-100 text-slate-400 border border-dashed border-slate-300 line-through cursor-not-allowed opacity-60'
                                            : (selectedTime === slot
                                                ? 'bg-indigo-600 text-white font-bold shadow-2xs ring-2 ring-indigo-500/20'
                                                : 'border border-slate-200 bg-white hover:border-indigo-400 hover:bg-indigo-50/40 text-slate-700 font-semibold cursor-pointer')"
                                        class="py-2.5 px-1 text-xs rounded-xl text-center transition flex flex-col items-center justify-center gap-0.5 relative"
                                    >
                                        <span x-text="slot"></span>
                                        <span
                                            x-show="isSlotBooked(slot)"
                                            class="text-[9px] font-bold text-rose-500 uppercase tracking-tighter"
                                        >Booked</span>
                                    </button>
                                </template>
                            </div>

                            <!-- Availability Legend -->
                            <div class="mt-3 flex items-center gap-4 text-[11px] text-slate-400 pt-2 border-t border-slate-100">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                    Available
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-slate-300 inline-block"></span>
                                    <span class="line-through">Booked / Unavailable</span>
                                </span>
                            </div>

                            <!-- Full Day Booked Warning Notice -->
                            <div
                                x-show="availableSlotsCount() === 0"
                                x-cloak
                                style="display: none;"
                                class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium"
                            >
                                All slots for this stylist are fully booked on this date. Please select another date or stylist above.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 Navigation Strip -->
                <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-2xs">
                    <button
                        type="button"
                        @click="prevStep()"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs sm:text-sm font-semibold text-slate-700 transition cursor-pointer"
                    >
                        &larr; Back to Services
                    </button>

                    <button
                        type="button"
                        @click="nextStep()"
                        :disabled="!canGoToStep3()"
                        :class="canGoToStep3()
                            ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-600/20 cursor-pointer'
                            : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition inline-flex items-center gap-1.5"
                    >
                        <span>Continue to Your Details</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </div>

            <!-- STEP 3: CLIENT DETAILS -->
            <div x-show="step === 3" x-transition.opacity class="space-y-6" style="display: none;">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 shadow-2xs space-y-6">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Your Contact Details</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">We'll use your Sri Lankan phone number to confirm your booking.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name *</label>
                            <input
                                type="text"
                                x-model="clientName"
                                placeholder="e.g. Kasun Fernando"
                                required
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Mobile Phone (Sri Lanka) *</label>
                            <input
                                type="text"
                                x-model="clientPhone"
                                placeholder="e.g. 077 123 4567 or +94 77 123 4567"
                                required
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Accepts standard 07X or +947X numbers.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address (Optional)</label>
                            <input
                                type="email"
                                x-model="clientEmail"
                                placeholder="e.g. kasun@example.com"
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                            >
                        </div>

                        <!-- Gender Card Selector -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Client Gender *</label>
                            <div class="grid grid-cols-3 gap-3">
                                <button
                                    type="button"
                                    @click="clientGender = 'female'; genderFilter = 'female'"
                                    :class="clientGender === 'female'
                                        ? 'border-pink-500 bg-pink-50 text-pink-700 shadow-2xs font-bold'
                                        : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                    class="rounded-xl border-2 p-3 text-center transition cursor-pointer text-xs"
                                >
                                    ♀ Female
                                </button>
                                <button
                                    type="button"
                                    @click="clientGender = 'male'; genderFilter = 'male'"
                                    :class="clientGender === 'male'
                                        ? 'border-sky-500 bg-sky-50 text-sky-700 shadow-2xs font-bold'
                                        : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                    class="rounded-xl border-2 p-3 text-center transition cursor-pointer text-xs"
                                >
                                    ♂ Male
                                </button>
                                <button
                                    type="button"
                                    @click="clientGender = 'other'"
                                    :class="clientGender === 'other'
                                        ? 'border-indigo-500 bg-indigo-50 text-indigo-700 shadow-2xs font-bold'
                                        : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                    class="rounded-xl border-2 p-3 text-center transition cursor-pointer text-xs"
                                >
                                    ⚥ Other
                                </button>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Special Requests or Notes (Optional)</label>
                            <textarea
                                x-model="notes"
                                rows="2"
                                placeholder="Any allergies, style references, or timing preferences..."
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 3 Navigation Strip -->
                <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-2xs">
                    <button
                        type="button"
                        @click="prevStep()"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs sm:text-sm font-semibold text-slate-700 transition cursor-pointer"
                    >
                        &larr; Back to Schedule
                    </button>

                    <button
                        type="button"
                        @click="nextStep()"
                        :disabled="!canGoToStep4()"
                        :class="canGoToStep4()
                            ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-600/20 cursor-pointer'
                            : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition inline-flex items-center gap-1.5"
                    >
                        <span>Review Booking Details</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </div>

            <!-- STEP 4: CONFIRMATION & SUBMIT -->
            <div x-show="step === 4" x-transition.opacity class="space-y-6" style="display: none;">
                <form method="POST" action="{{ route('booking.store') }}" class="space-y-6">
                    @csrf
                    <!-- Hidden Form Submissions Bound from Alpine State -->
                    <input type="hidden" name="service_id" :value="selectedService ? selectedService.id : ''">
                    <input type="hidden" name="service_name" :value="selectedService ? selectedService.name : ''">
                    <input type="hidden" name="staff_id" :value="selectedStaffId || ''">
                    <input type="hidden" name="date" :value="selectedDate">
                    <input type="hidden" name="start_time" :value="selectedTime">
                    <input type="hidden" name="client_name" :value="clientName">
                    <input type="hidden" name="client_phone" :value="clientPhone">
                    <input type="hidden" name="client_gender" :value="clientGender">
                    <input type="hidden" name="client_email" :value="clientEmail">
                    <input type="hidden" name="notes" :value="notes">

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 mb-2">
                                Ready to Book
                            </span>
                            <h2 class="text-xl font-bold text-slate-900">Review & Confirm Your Appointment</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Please review the summary below before completing your reservation.</p>
                        </div>

                        <!-- Summary Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Service Summary Box -->
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 space-y-2">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Selected Treatment</div>
                                <div class="text-base font-bold text-slate-900" x-text="selectedService ? selectedService.name : ''"></div>
                                <div class="flex items-center justify-between text-xs text-slate-600 pt-2 border-t border-slate-200/60">
                                    <span>Duration: <strong x-text="selectedService ? selectedService.duration_minutes : ''"></strong> mins</span>
                                    <span class="text-sm font-bold text-emerald-700">Rs. <span x-text="formatPrice(selectedService ? selectedService.price : 0)"></span></span>
                                </div>
                            </div>

                            <!-- Stylist & Time Box -->
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 space-y-2">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Stylist & Schedule</div>
                                <div class="text-base font-bold text-slate-900" x-text="selectedStaff && selectedStaff.user ? selectedStaff.user.name : 'Salon Stylist'"></div>
                                <div class="flex items-center justify-between text-xs text-slate-600 pt-2 border-t border-slate-200/60">
                                    <span>Date: <strong x-text="selectedDate"></strong></span>
                                    <span>Time: <strong x-text="selectedTime"></strong></span>
                                </div>
                            </div>

                            <!-- Client Info Box -->
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 space-y-1 sm:col-span-2">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Client Contact</div>
                                <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                                    <div>
                                        <span class="font-bold text-slate-900 text-sm" x-text="clientName"></span>
                                        <span class="text-slate-400 ml-1">(<span x-text="clientGender"></span>)</span>
                                    </div>
                                    <div class="font-mono font-medium text-slate-700" x-text="clientPhone"></div>
                                </div>
                                <div x-show="clientEmail" class="text-xs text-slate-500 pt-1" x-text="clientEmail"></div>
                                <div x-show="notes" class="text-xs text-slate-500 italic pt-1 border-t border-slate-200/60 mt-2">
                                    Note: "<span x-text="notes"></span>"
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-900 flex items-start gap-2.5">
                            <span class="text-base">ℹ️</span>
                            <p class="leading-relaxed">
                                No advance payment required now. Your appointment will be held and payment is settled at the salon after your service.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 Submission Actions Strip -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-2xs">
                        <button
                            type="button"
                            @click="prevStep()"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs sm:text-sm font-semibold text-slate-700 transition cursor-pointer"
                        >
                            &larr; Back to Edit
                        </button>

                        <button
                            type="submit"
                            class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition cursor-pointer inline-flex items-center gap-2"
                        >
                            <span>Confirm & Schedule Appointment</span>
                            <span>✓</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Client-Facing Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        <div class="max-w-5xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Salon HQ. All rights reserved. Unisex Salon & Luxury Spa.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>

