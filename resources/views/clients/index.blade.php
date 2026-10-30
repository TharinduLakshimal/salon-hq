@extends('layouts.app')

@section('title', 'Clients & Loyalty CRM')

@section('header-left')
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl font-bold text-slate-900 truncate">Clients & Loyalty CRM</h1>
        <p class="hidden sm:block text-xs text-slate-500 mt-0.5 truncate">
            Manage unisex client profiles, styling formulas, loyalty points, and visit history.
        </p>
    </div>
@endsection

@section('header-right')
    <button
        type="button"
        @click="$dispatch('open-client-modal')"
        class="inline-flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 shrink-0 whitespace-nowrap transition cursor-pointer"
    >
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Add New Client</span>
    </button>
@endsection

@section('content')
    <script>
        window.createClientsDirectory = function (config) {
            const defaultStylistName = (config.stylists[0] && config.stylists[0].name) || 'Kavindi Senanayake';
            const gradients = [
                'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
                'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
                'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
                'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            ];

            return {
                clients: config.clients,
                stylists: config.stylists,

                tierFilter: 'all',
                genderFilter: 'all',
                stylistFilter: 'all',
                sortBy: 'spend_desc',
                search: '',
                viewMode: 'grid',
                currentPage: 1,
                perPage: 5,

                showFormModal: Boolean(config.openModalOnLoad),
                editingClientId: null,
                selectedClient: null,
                clientToDelete: null,
                toastMessage: '',

                tierTabs: [
                    { key: 'all', label: 'All Clients' },
                    { key: 'VIP Platinum', label: 'VIP Platinum' },
                    { key: 'Gold Member', label: 'Gold Member' },
                    { key: 'Regular', label: 'Regular' },
                    { key: 'New Client', label: 'New Client' },
                ],

                form: {
                    name: '',
                    phone_number: '',
                    email: '',
                    gender: 'female',
                    date_of_birth: '',
                    address: '',
                    preferred_stylist: defaultStylistName,
                    membership_tier: 'New Client',
                    loyalty_points: 100,
                    hair_skin_notes: '',
                },

                get baseFilteredWithoutTier() {
                    const q = this.search.trim().toLowerCase();

                    return this.clients.filter((client) => {
                        if (this.genderFilter !== 'all' && client.gender !== this.genderFilter) {
                            return false;
                        }
                        if (this.stylistFilter !== 'all' && client.preferred_stylist !== this.stylistFilter) {
                            return false;
                        }
                        if (q !== '') {
                            const haystack = [
                                client.client_code,
                                client.name,
                                client.phone_number,
                                client.email,
                                client.address,
                                client.preferred_stylist,
                                client.hair_skin_notes,
                            ].join(' ').toLowerCase();

                            if (!haystack.includes(q)) {
                                return false;
                            }
                        }

                        return true;
                    });
                },

                get filteredClients() {
                    const list = this.tierFilter === 'all'
                        ? [...this.baseFilteredWithoutTier]
                        : this.baseFilteredWithoutTier.filter((c) => c.membership_tier === this.tierFilter);

                    return list.sort((a, b) => {
                        if (this.sortBy === 'visits_desc') {
                            return Number(b.total_visits) - Number(a.total_visits);
                        }
                        if (this.sortBy === 'points_desc') {
                            return Number(b.loyalty_points) - Number(a.loyalty_points);
                        }
                        if (this.sortBy === 'name_asc') {
                            return a.name.localeCompare(b.name);
                        }

                        return Number(b.total_spent) - Number(a.total_spent);
                    });
                },

                init() {
                    this.$watch('tierFilter', () => { this.currentPage = 1; });
                    this.$watch('genderFilter', () => { this.currentPage = 1; });
                    this.$watch('stylistFilter', () => { this.currentPage = 1; });
                    this.$watch('sortBy', () => { this.currentPage = 1; });
                    this.$watch('search', () => { this.currentPage = 1; });
                    this.$watch('perPage', () => { this.currentPage = 1; });
                },

                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredClients.length / Number(this.perPage || 5)));
                },

                get safeCurrentPage() {
                    return Math.min(Math.max(1, this.currentPage), this.totalPages);
                },

                get paginatedTableClients() {
                    const size = Number(this.perPage || 5);
                    const start = (this.safeCurrentPage - 1) * size;

                    return this.filteredClients.slice(start, start + size);
                },

                get paginationStart() {
                    if (this.filteredClients.length === 0) {
                        return 0;
                    }

                    return (this.safeCurrentPage - 1) * Number(this.perPage || 5) + 1;
                },

                get paginationEnd() {
                    return Math.min(
                        this.safeCurrentPage * Number(this.perPage || 5),
                        this.filteredClients.length
                    );
                },

                get visiblePages() {
                    const total = this.totalPages;
                    const current = this.safeCurrentPage;

                    if (total <= 7) {
                        return Array.from({ length: total }, (_, idx) => idx + 1);
                    }

                    if (current <= 4) {
                        return [1, 2, 3, 4, 5, '...', total];
                    }

                    if (current >= total - 3) {
                        return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
                    }

                    return [1, '...', current - 1, current, current + 1, '...', total];
                },

                goToPage(page) {
                    if (typeof page !== 'number') {
                        return;
                    }
                    this.currentPage = Math.min(Math.max(1, page), this.totalPages);
                },

                get stats() {
                    const list = this.filteredClients;
                    const totalSpend = list.reduce((sum, c) => sum + Number(c.total_spent || 0), 0);

                    return {
                        total: list.length,
                        femaleCount: list.filter((c) => c.gender === 'female').length,
                        maleCount: list.filter((c) => c.gender === 'male').length,
                        vipAndGold: list.filter(
                            (c) => c.membership_tier === 'VIP Platinum' || c.membership_tier === 'Gold Member'
                        ).length,
                        totalPoints: list.reduce((sum, c) => sum + Number(c.loyalty_points || 0), 0),
                        totalSpend: totalSpend,
                        averageSpend: list.length > 0 ? Math.round(totalSpend / list.length) : 0,
                    };
                },

                get hasActiveFilters() {
                    return (
                        this.tierFilter !== 'all' ||
                        this.genderFilter !== 'all' ||
                        this.stylistFilter !== 'all' ||
                        this.search.trim() !== ''
                    );
                },

                countByTier(tierKey) {
                    if (tierKey === 'all') {
                        return this.baseFilteredWithoutTier.length;
                    }

                    return this.baseFilteredWithoutTier.filter((c) => c.membership_tier === tierKey).length;
                },

                resetFilters() {
                    this.tierFilter = 'all';
                    this.genderFilter = 'all';
                    this.stylistFilter = 'all';
                    this.search = '';
                },

                formatNumber(val) {
                    return Number(val || 0).toLocaleString('en-US');
                },

                formatCompactSpend(val) {
                    return Number(val || 0).toLocaleString('en-US');
                },

                formatDobLabel(dateStr) {
                    if (!dateStr) {
                        return '';
                    }
                    const parsed = new Date(dateStr + 'T00:00:00');
                    if (Number.isNaN(parsed.getTime())) {
                        return dateStr;
                    }

                    return parsed.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                },

                tierBadgeClass(tier) {
                    const classes = {
                        'VIP Platinum': 'bg-purple-100 text-purple-700',
                        'Gold Member': 'bg-amber-100 text-amber-700',
                        'Regular': 'bg-emerald-100 text-emerald-700',
                        'New Client': 'bg-sky-100 text-sky-700',
                    };

                    return classes[tier] || 'bg-slate-100 text-slate-700';
                },

                computeInitials(name) {
                    const parts = String(name || 'CL').trim().split(/\s+/).slice(0, 2);
                    const initials = parts.map((p) => (p[0] || '').toUpperCase()).join('');

                    return initials || 'CL';
                },

                openCreateModal() {
                    this.editingClientId = null;
                    Object.assign(this.form, {
                        name: '',
                        phone_number: '',
                        email: '',
                        gender: 'female',
                        date_of_birth: '',
                        address: '',
                        preferred_stylist: defaultStylistName,
                        membership_tier: 'New Client',
                        loyalty_points: 100,
                        hair_skin_notes: '',
                    });
                    this.showFormModal = true;
                },

                openEditModal(client) {
                    this.editingClientId = client.id;
                    Object.assign(this.form, {
                        name: client.name,
                        phone_number: client.phone_number,
                        email: client.email || '',
                        gender: client.gender || 'female',
                        date_of_birth: client.date_of_birth || '',
                        address: client.address || '',
                        preferred_stylist: client.preferred_stylist || defaultStylistName,
                        membership_tier: client.membership_tier || 'Regular',
                        loyalty_points: Number(client.loyalty_points || 0),
                        hair_skin_notes: client.hair_skin_notes || '',
                    });
                    this.showFormModal = true;
                },

                saveClient() {
                    if (this.editingClientId) {
                        const existing = this.clients.find((c) => c.id === this.editingClientId);
                        if (existing) {
                            Object.assign(existing, {
                                name: this.form.name.trim(),
                                phone_number: this.form.phone_number.trim(),
                                email: this.form.email.trim(),
                                gender: this.form.gender,
                                date_of_birth: this.form.date_of_birth,
                                address: this.form.address.trim(),
                                preferred_stylist: this.form.preferred_stylist,
                                membership_tier: this.form.membership_tier,
                                loyalty_points: Number(this.form.loyalty_points || 0),
                                hair_skin_notes: this.form.hair_skin_notes.trim(),
                                avatar_initials: this.computeInitials(this.form.name),
                            });
                            this.toastMessage = `Client profile for ${existing.name} updated.`;
                        }
                    } else {
                        const nextId = this.clients.reduce((max, item) => Math.max(max, item.id), 0) + 1;
                        const codeNumber = String(nextId).padStart(4, '0');
                        const newClient = {
                            id: nextId,
                            client_code: `CL-2026-${codeNumber}`,
                            name: this.form.name.trim(),
                            phone_number: this.form.phone_number.trim(),
                            email: this.form.email.trim(),
                            gender: this.form.gender,
                            date_of_birth: this.form.date_of_birth,
                            address: this.form.address.trim() || 'Colombo',
                            preferred_stylist: this.form.preferred_stylist,
                            membership_tier: this.form.membership_tier,
                            loyalty_points: Number(this.form.loyalty_points || 100),
                            total_visits: 0,
                            total_spent: 0,
                            last_visit: 'New Registration',
                            hair_skin_notes: this.form.hair_skin_notes.trim(),
                            avatar_initials: this.computeInitials(this.form.name),
                            avatar_gradient: gradients[nextId % gradients.length],
                            recent_services: [],
                        };

                        this.clients.unshift(newClient);
                        this.currentPage = 1;
                        this.toastMessage = `New client ${newClient.name} (${newClient.client_code}) registered.`;
                    }

                    this.showFormModal = false;
                    this.editingClientId = null;
                },

                awardLoyaltyPoints(clientId, bonusPoints) {
                    const target = this.clients.find((c) => c.id === clientId);
                    if (!target) {
                        return;
                    }

                    target.loyalty_points = Number(target.loyalty_points || 0) + Number(bonusPoints);
                    this.toastMessage = `Added +${bonusPoints} loyalty points to ${target.name}.`;
                },

                deleteClient() {
                    if (!this.clientToDelete) {
                        return;
                    }

                    const deletedName = this.clientToDelete.name;
                    const deletedId = this.clientToDelete.id;
                    this.clients = this.clients.filter((c) => c.id !== deletedId);
                    this.currentPage = Math.min(this.currentPage, this.totalPages);
                    if (this.selectedClient && this.selectedClient.id === deletedId) {
                        this.selectedClient = null;
                    }
                    this.clientToDelete = null;
                    this.toastMessage = `Client profile for ${deletedName} removed.`;
                },
            };
        };
    </script>

    <div
        x-data="window.createClientsDirectory({
            clients: @js($clients),
            stylists: @js($stylistsList),
            openModalOnLoad: @js($openModalOnLoad),
        })"
        @open-client-modal.window="openCreateModal()"
        @keydown.escape.window="showFormModal = false; selectedClient = null; clientToDelete = null"
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

        @include('clients.includes.kpi-cards')
        @include('clients.includes.toolbar-filters')
        @include('clients.includes.clients-grid')
        @include('clients.includes.clients-table')
        @include('clients.includes.client-modals')
    </div>
@endsection

