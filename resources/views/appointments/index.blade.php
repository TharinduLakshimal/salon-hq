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
                id: null,
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
                csrfToken: config.csrfToken,
                routes: config.routes,

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
                toastErrorMessage: '',

                isSubmitting: false,
                updatingBookingId: null,
                isLookingUpPhone: false,
                lookupRequestId: 0,
                formErrors: {},
                formErrorBanner: '',

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
                    client_id: null,
                    client_name: '',
                    client_phone: '',
                    client_gender: firstService.gender === 'male' ? 'male' : 'female',
                    service_name: firstService.name,
                    category: firstService.category,
                    duration_minutes: firstService.duration,
                    price: firstService.price,
                    staff_id: firstStylist.id,
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

                formatCustomDateLabel(dateStr) {
                    const parsed = new Date(dateStr + 'T00:00:00');
                    if (Number.isNaN(parsed.getTime())) {
                        return dateStr;
                    }

                    return parsed.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
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

                async updateStatus(id, newStatus) {
                    if (this.updatingBookingId === id) {
                        return;
                    }

                    this.updatingBookingId = id;
                    this.toastErrorMessage = '';

                    try {
                        const response = await fetch(`${this.routes.baseUrl}/${id}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                            },
                            body: JSON.stringify({ status: newStatus }),
                        });

                        if (response.status === 200) {
                            const data = await response.json();
                            const index = this.appointments.findIndex((item) => item.id === id);
                            if (index !== -1) {
                                this.appointments.splice(index, 1, data.appointment);
                            }
                            if (this.selectedBooking && this.selectedBooking.id === id) {
                                this.selectedBooking = data.appointment;
                            }
                            this.showToast(data.message || `Booking marked as ${this.statusLabel(newStatus)}.`);
                        } else {
                            this.toastErrorMessage = 'Could not update booking status. Please try again.';
                        }
                    } catch (error) {
                        this.toastErrorMessage = 'Network error while updating booking status.';
                    } finally {
                        this.updatingBookingId = null;
                    }
                },

                openNewBookingModal(preferredSlot = null) {
                    const defaultService = this.services[0] || firstService;
                    const defaultStylist = this.stylists[0] || firstStylist;

                    this.formErrors = {};
                    this.formErrorBanner = '';
                    this.isLookingUpPhone = false;
                    this.lookupRequestId++;

                    Object.assign(this.form, {
                        client_id: null,
                        client_name: '',
                        client_phone: '',
                        client_gender: defaultService.gender === 'male' ? 'male' : 'female',
                        service_name: defaultService.name,
                        category: defaultService.category,
                        duration_minutes: defaultService.duration,
                        price: defaultService.price,
                        staff_id: defaultStylist.id,
                        stylist_name: defaultStylist.name,
                        booking_type: 'Walk-In',
                        date: this.dateFilter !== 'all' ? this.dateFilter : this.todayDate,
                        start_time: preferredSlot || '10:00 AM',
                        notes: '',
                    });

                    this.showBookingModal = true;
                },

                async lookupClientByPhone() {
                    const stripped = String(this.form.client_phone || '').replace(/[\s\-()]+/g, '');
                    const requestId = ++this.lookupRequestId;

                    if (stripped.length < 9) {
                        if (this.form.client_id !== null) {
                            this.form.client_id = null;
                            this.form.client_name = '';
                        }
                        this.isLookingUpPhone = false;
                        return;
                    }

                    this.isLookingUpPhone = true;

                    try {
                        const response = await fetch(
                            `${this.routes.searchPhone}?phone=${encodeURIComponent(stripped)}`,
                            {
                                headers: {
                                    'Accept': 'application/json',
                                },
                            }
                        );

                        if (requestId !== this.lookupRequestId) {
                            return;
                        }

                        if (response.status === 200) {
                            const data = await response.json();

                            if (data.found && data.user) {
                                this.form.client_id = data.user.id;
                                this.form.client_name = data.user.name;

                                if (data.user.gender === 'female' || data.user.gender === 'male') {
                                    this.selectClientGender(data.user.gender);
                                }
                            } else {
                                if (this.form.client_id !== null) {
                                    this.form.client_id = null;
                                    this.form.client_name = '';
                                } else {
                                    this.form.client_id = null;
                                }
                            }
                        }
                    } catch (error) {
                        if (requestId === this.lookupRequestId && this.form.client_id !== null) {
                            this.form.client_id = null;
                            this.form.client_name = '';
                        }
                    } finally {
                        if (requestId === this.lookupRequestId) {
                            this.isLookingUpPhone = false;
                        }
                    }
                },

                selectClientGender(gender) {
                    this.form.client_gender = gender;

                    const currentService = this.services.find((s) => s.name === this.form.service_name);
                    if (currentService && currentService.gender !== 'both' && currentService.gender !== gender) {
                        const compatibleService = this.services.find(
                            (s) => s.gender === gender || s.gender === 'both'
                        );

                        if (compatibleService) {
                            this.form.service_name = compatibleService.name;
                            this.form.category = compatibleService.category;
                            this.form.duration_minutes = compatibleService.duration;
                            this.form.price = compatibleService.price;

                            const matchingStylist = this.stylists.find(
                                (st) => st.category === compatibleService.category
                            );
                            if (matchingStylist) {
                                this.form.staff_id = matchingStylist.id;
                                this.form.stylist_name = matchingStylist.name;
                            }
                        }
                    }
                },

                onServiceChange() {
                    const found = this.services.find((s) => s.name === this.form.service_name);
                    if (!found) {
                        return;
                    }

                    this.form.category = found.category;
                    this.form.duration_minutes = found.duration;
                    this.form.price = found.price;

                    if (this.form.client_id === null && (found.gender === 'female' || found.gender === 'male')) {
                        this.form.client_gender = found.gender;
                    }

                    const matchingStylist = this.stylists.find((st) => st.category === found.category);
                    if (matchingStylist) {
                        this.form.staff_id = matchingStylist.id;
                        this.form.stylist_name = matchingStylist.name;
                    }
                },

                onStylistChange() {
                    const selected = this.stylists.find((st) => Number(st.id) === Number(this.form.staff_id));
                    if (selected) {
                        this.form.stylist_name = selected.name;
                    }
                },

                async submitNewBooking() {
                    if (this.isSubmitting) {
                        return;
                    }

                    this.formErrors = {};
                    this.formErrorBanner = '';
                    this.isSubmitting = true;

                    try {
                        const response = await fetch(this.routes.store, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                            },
                            body: JSON.stringify(this.form),
                        });

                        if (response.status === 200) {
                            const data = await response.json();
                            this.appointments.unshift(data.appointment);
                            this.showBookingModal = false;
                            this.showToast(
                                data.message ||
                                    `Booking ${data.appointment.booking_code} confirmed for ${data.appointment.client_name}.`
                            );
                            return;
                        }

                        if (response.status === 422) {
                            const errorPayload = await response.json();
                            this.formErrors = errorPayload.errors || {};
                            this.formErrorBanner = errorPayload.message || 'Please fix the highlighted validation errors.';
                            return;
                        }

                        this.formErrorBanner = 'Unable to save the appointment right now. Please try again.';
                    } catch (error) {
                        this.formErrorBanner = 'Network error while saving the appointment.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                showToast(message) {
                    this.toastErrorMessage = '';
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
            csrfToken: @js(csrf_token()),
            routes: {
                store: @js(route('appointments.store')),
                baseUrl: @js(url('/appointments')),
                searchPhone: @js(route('clients.search-phone')),
            },
        })"
        @open-booking-modal.window="openNewBookingModal()"
        @keydown.escape.window="showBookingModal = false; selectedBooking = null"
    >
        <!-- Success Toast Banner -->
        <div
            x-show="toastMessage !== ''"
            x-transition
            class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-semibold text-emerald-800 flex items-center justify-between gap-3"
            style="display: none;"
        >
            <span class="truncate">✅ <span x-text="toastMessage"></span></span>
            <button type="button" @click="toastMessage = ''" class="text-emerald-600 hover:text-emerald-900 shrink-0 cursor-pointer">✕</button>
        </div>

        <!-- Error Toast Banner -->
        <div
            x-show="toastErrorMessage !== ''"
            x-transition
            class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-semibold text-red-700 flex items-center justify-between gap-3"
            style="display: none;"
        >
            <span class="truncate">⚠️ <span x-text="toastErrorMessage"></span></span>
            <button type="button" @click="toastErrorMessage = ''" class="text-red-600 hover:text-red-900 shrink-0 cursor-pointer">✕</button>
        </div>

        @include('appointments.includes.kpi-cards')
        @include('appointments.includes.toolbar-filters')
        @include('appointments.includes.bookings-table')
        @include('appointments.includes.timeline-schedule')
        @include('appointments.includes.new-appointment-modal')
    </div>
@endsection
