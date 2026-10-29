@extends('layouts.app')

@section('title', 'Appointments & Bookings')

@section('header-left')
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl font-bold text-slate-900 truncate">Appointments & Bookings</h1>
        <p class="hidden sm:block text-xs text-slate-500 mt-0.5 truncate">
            Manage daily salon chair schedules, walk-ins, and unisex client bookings.
        </p>
    </div>
@endsection

@section('header-right')
    <button
        type="button"
        @click="$dispatch('open-booking-modal')"
        class="inline-flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 shrink-0 whitespace-nowrap transition cursor-pointer"
    >
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>New Appointment</span>
    </button>
@endsection

@section('content')
    <script>
        window.createAppointmentsBoard = function (config) {
            const firstService = config.services[0] || {
                name: 'Butterfly Layer Cut & Blowout',
                category: 'Hair Stylists',
                duration: 60,
                price: 4500,
                gender: 'both',
            };
            const firstStylist = config.stylists[0] || {
                name: 'Salon Stylist',
                initials: 'SS',
                gradient: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                category: 'Hair Stylists',
            };

            return {
                appointments: config.appointments,
                services: config.services,
                stylists: config.stylists,
                todayDate: config.today,
                tomorrowDate: config.tomorrow,

                dateFilter: 'all',
                customDate: '',
                statusFilter: 'all',
                stylistFilter: 'all',
                categoryFilter: 'all',
                genderFilter: 'all',
                search: '',
                viewMode: 'list',

                showBookingModal: Boolean(config.openModalOnLoad),
                selectedBooking: null,
                toastMessage: '',

                statusTabs: [
                    { key: 'all', label: 'All' },
                    { key: 'in_progress', label: 'In Progress' },
                    { key: 'confirmed', label: 'Confirmed' },
                    { key: 'pending', label: 'Pending' },
                    { key: 'completed', label: 'Completed' },
                    { key: 'cancelled', label: 'Cancelled' },
                ],

                timeSlots: [
                    '09:00 AM',
                    '10:00 AM',
                    '11:00 AM',
                    '12:00 PM',
                    '01:00 PM',
                    '02:00 PM',
                    '03:00 PM',
                    '04:00 PM',
                    '05:00 PM',
                ],

                form: {
                    client_name: '',
                    client_phone: '',
                    client_gender: firstService.gender === 'male' ? 'male' : 'female',
                    service_name: firstService.name,
                    category: firstService.category,
                    duration_minutes: firstService.duration,
                    price: firstService.price,
                    stylist_name: firstStylist.name,
                    booking_type: 'Walk-In',
                    date: config.today,
                    start_time: '10:00 AM',
                    notes: '',
                },

                get baseFilteredWithoutStatus() {
                    const q = this.search.trim().toLowerCase();

                    return this.appointments.filter((item) => {
                        if (this.dateFilter !== 'all' && item.date !== this.dateFilter) {
                            return false;
                        }
                        if (this.stylistFilter !== 'all' && item.stylist_name !== this.stylistFilter) {
                            return false;
                        }
                        if (this.categoryFilter !== 'all' && item.category !== this.categoryFilter) {
                            return false;
                        }
                        if (this.genderFilter !== 'all' && item.client_gender !== this.genderFilter) {
                            return false;
                        }
                        if (q !== '') {
                            const haystack = [
                                item.booking_code,
                                item.client_name,
                                item.client_phone,
                                item.service_name,
                                item.stylist_name,
                                item.category,
                            ].join(' ').toLowerCase();

                            if (!haystack.includes(q)) {
                                return false;
                            }
                        }

                        return true;
                    });
                },

                get filteredAppointments() {
                    if (this.statusFilter === 'all') {
                        return this.baseFilteredWithoutStatus;
                    }

                    return this.baseFilteredWithoutStatus.filter((item) => item.status === this.statusFilter);
                },

                get stats() {
                    const list = this.filteredAppointments;

                    return {
                        total: list.length,
                        inProgress: list.filter((i) => i.status === 'in_progress').length,
                        confirmed: list.filter((i) => i.status === 'confirmed').length,
                        pending: list.filter((i) => i.status === 'pending').length,
                        completed: list.filter((i) => i.status === 'completed').length,
                        revenue: list
                            .filter((i) => i.status !== 'cancelled')
                            .reduce((sum, i) => sum + Number(i.price || 0), 0),
                        completedRevenue: list
                            .filter((i) => i.status === 'completed')
                            .reduce((sum, i) => sum + Number(i.price || 0), 0),
                    };
                },

                get hasActiveFilters() {
                    return (
                        this.dateFilter !== 'all' ||
                        this.statusFilter !== 'all' ||
                        this.stylistFilter !== 'all' ||
                        this.categoryFilter !== 'all' ||
                        this.genderFilter !== 'all' ||
                        this.search.trim() !== ''
                    );
                },

                countByStatus(statusKey) {
                    if (statusKey === 'all') {
                        return this.baseFilteredWithoutStatus.length;
                    }

                    return this.baseFilteredWithoutStatus.filter((item) => item.status === statusKey).length;
                },

                appointmentsForSlot(slot) {
                    return this.filteredAppointments.filter((item) => item.hour_key === slot);
                },

                resetFilters() {
                    this.dateFilter = 'all';
                    this.customDate = '';
                    this.statusFilter = 'all';
                    this.stylistFilter = 'all';
                    this.categoryFilter = 'all';
                    this.genderFilter = 'all';
                    this.search = '';
                },

                formatCurrency(amount) {
                    return Number(amount || 0).toLocaleString('en-US');
                },

                formatDateLabel(dateStr) {
                    if (dateStr === this.todayDate) {
                        return 'Today';
                    }
                    if (dateStr === this.tomorrowDate) {
                        return 'Tomorrow';
                    }

                    const parsed = new Date(dateStr + 'T00:00:00');
                    if (Number.isNaN(parsed.getTime())) {
                        return dateStr;
                    }

                    return parsed.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                },

                statusLabel(status) {
                    const labels = {
                        in_progress: 'In Progress',
                        confirmed: 'Confirmed',
                        pending: 'Pending',
                        completed: 'Completed',
                        cancelled: 'Cancelled',
                    };

                    return labels[status] || status;
                },

                statusBadgeClass(status) {
                    const classes = {
                        in_progress: 'bg-blue-100 text-blue-700',
                        confirmed: 'bg-emerald-100 text-emerald-700',
                        pending: 'bg-amber-100 text-amber-700',
                        completed: 'bg-indigo-100 text-indigo-700',
                        cancelled: 'bg-red-100 text-red-700',
                    };

                    return classes[status] || 'bg-slate-100 text-slate-700';
                },

                updateStatus(id, newStatus) {
                    const booking = this.appointments.find((item) => item.id === id);
                    if (!booking) {
                        return;
                    }

                    booking.status = newStatus;
                    this.showToast(`Booking ${booking.booking_code} marked as ${this.statusLabel(newStatus)}.`);
                },

                openNewBookingModal(preferredSlot = null) {
                    const defaultService = this.services[0] || firstService;
                    const defaultStylist = this.stylists[0] || firstStylist;

                    this.form = {
                        client_name: '',
                        client_phone: '',
                        client_gender: defaultService.gender === 'male' ? 'male' : 'female',
                        service_name: defaultService.name,
                        category: defaultService.category,
                        duration_minutes: defaultService.duration,
                        price: defaultService.price,
                        stylist_name: defaultStylist.name,
                        booking_type: 'Walk-In',
                        date: this.dateFilter !== 'all' ? this.dateFilter : this.todayDate,
                        start_time: preferredSlot || '10:00 AM',
                        notes: '',
                    };

                    this.showBookingModal = true;
                },

                onServiceChange() {
                    const found = this.services.find((s) => s.name === this.form.service_name);
                    if (!found) {
                        return;
                    }

                    this.form.category = found.category;
                    this.form.duration_minutes = found.duration;
                    this.form.price = found.price;

                    if (found.gender === 'female' || found.gender === 'male') {
                        this.form.client_gender = found.gender;
                    }

                    const matchingStylist = this.stylists.find((st) => st.category === found.category);
                    if (matchingStylist) {
                        this.form.stylist_name = matchingStylist.name;
                    }
                },

                normalizeHourKey(startTime) {
                    const match = /^(\d{2}):\d{2}\s+(AM|PM)$/.exec(startTime);
                    if (!match) {
                        return '09:00 AM';
                    }

                    return `${match[1]}:00 ${match[2]}`;
                },

                computeEndTime(startTime, durationMinutes) {
                    const match = /^(\d{2}):(\d{2})\s+(AM|PM)$/.exec(startTime);
                    if (!match) {
                        return '';
                    }

                    let hours = parseInt(match[1], 10);
                    const minutes = parseInt(match[2], 10);
                    const period = match[3];

                    if (period === 'PM' && hours !== 12) {
                        hours += 12;
                    } else if (period === 'AM' && hours === 12) {
                        hours = 0;
                    }

                    const totalMinutes = hours * 60 + minutes + Number(durationMinutes || 60);
                    const endHours24 = Math.floor(totalMinutes / 60) % 24;
                    const endMinutes = totalMinutes % 60;
                    const endPeriod = endHours24 >= 12 ? 'PM' : 'AM';
                    const endHours12 = endHours24 % 12 === 0 ? 12 : endHours24 % 12;

                    return `${String(endHours12).padStart(2, '0')}:${String(endMinutes).padStart(2, '0')} ${endPeriod}`;
                },

                submitNewBooking() {
                    const nextId = this.appointments.reduce((max, item) => Math.max(max, item.id), 0) + 1;
                    const codeNumber = String(100 + nextId).padStart(4, '0');
                    const stylistObj = this.stylists.find((s) => s.name === this.form.stylist_name) || firstStylist;

                    const newRecord = {
                        id: nextId,
                        booking_code: `BK-2026-${codeNumber}`,
                        client_name: this.form.client_name.trim(),
                        client_phone: this.form.client_phone.trim(),
                        client_gender: this.form.client_gender,
                        booking_type: this.form.booking_type,
                        service_name: this.form.service_name,
                        category: this.form.category,
                        duration_minutes: Number(this.form.duration_minutes),
                        stylist_name: stylistObj.name,
                        stylist_initials: stylistObj.initials,
                        stylist_gradient: stylistObj.gradient,
                        date: this.form.date,
                        start_time: this.form.start_time,
                        end_time: this.computeEndTime(this.form.start_time, this.form.duration_minutes),
                        hour_key: this.normalizeHourKey(this.form.start_time),
                        price: Number(this.form.price),
                        status: 'confirmed',
                        notes: this.form.notes.trim(),
                    };

                    this.appointments.unshift(newRecord);
                    this.showBookingModal = false;
                    this.showToast(`Booking ${newRecord.booking_code} confirmed for ${newRecord.client_name}.`);
                },

                showToast(message) {
                    this.toastMessage = message;
                },
            };
        };
    </script>

    <div
        x-data="window.createAppointmentsBoard({
            appointments: @js($appointments),
            services: @js($serviceCatalog),
            stylists: @js($stylistsList),
            today: @js($today),
            tomorrow: @js($tomorrow),
            openModalOnLoad: @js($openModalOnLoad),
        })"
        @open-booking-modal.window="openNewBookingModal()"
        @keydown.escape.window="showBookingModal = false; selectedBooking = null"
    >
        <!-- Interactive Toast Banner -->
        <div
            x-show="toastMessage !== ''"
            x-transition
            class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-semibold text-emerald-800 flex items-center justify-between gap-3"
            style="display: none;"
        >
            <span class="truncate">✅ <span x-text="toastMessage"></span></span>
            <button type="button" @click="toastMessage = ''" class="text-emerald-600 hover:text-emerald-900 shrink-0 cursor-pointer">✕</button>
        </div>

        @include('appointments.includes.kpi-cards')
        @include('appointments.includes.toolbar-filters')
        @include('appointments.includes.bookings-table')
        @include('appointments.includes.timeline-schedule')
        @include('appointments.includes.new-appointment-modal')
    </div>
@endsection

