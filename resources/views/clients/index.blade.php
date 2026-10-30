@extends('layouts.app')

@section('title', 'Clients & Loyalty CRM')

@section('header-left')
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl font-bold text-slate-900 truncate">Clients & Loyalty CRM</h1>
        <p class="hidden sm:block text-xs text-slate-500 mt-0.5 truncate">
            Connected to MySQL — manage unisex client accounts, service history, and loyalty tiers.
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
            return {
                clients: config.clients,
                stylists: config.stylists,
                storeClientUrl: config.storeUrl,
                baseClientsUrl: config.baseUrl,

                tierFilter: 'all',
                genderFilter: 'all',
                stylistFilter: 'all',
                sortBy: 'newest',
                search: '',
                viewMode: 'grid',
                currentPage: 1,
                perPage: 5,

                showFormModal: Boolean(config.openModalOnLoad || config.hasErrors),
                editingClientId: config.oldInput.editing_client_id ? Number(config.oldInput.editing_client_id) : null,
                selectedClient: null,
                clientToDelete: null,

                tierTabs: [
                    { key: 'all', label: 'All Clients' },
                    { key: 'VIP Platinum', label: 'VIP Platinum' },
                    { key: 'Gold Member', label: 'Gold Member' },
                    { key: 'Regular', label: 'Regular' },
                    { key: 'New Client', label: 'New Client' },
                ],

                form: {
                    name: config.oldInput.name || '',
                    phone_number: config.oldInput.phone_number || '',
                    email: config.oldInput.email || '',
                    gender: config.oldInput.gender || 'female',
                },

                init() {
                    this.$watch('tierFilter', () => { this.currentPage = 1; });
                    this.$watch('genderFilter', () => { this.currentPage = 1; });
                    this.$watch('stylistFilter', () => { this.currentPage = 1; });
                    this.$watch('sortBy', () => { this.currentPage = 1; });
                    this.$watch('search', () => { this.currentPage = 1; });
                    this.$watch('perPage', () => { this.currentPage = 1; });
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
                                client.preferred_stylist,
                                client.latest_service_summary,
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
                        if (this.sortBy === 'spend_desc') {
                            return Number(b.total_spent) - Number(a.total_spent);
                        }
                        if (this.sortBy === 'visits_desc') {
                            return Number(b.total_visits) - Number(a.total_visits);
                        }
                        if (this.sortBy === 'points_desc') {
                            return Number(b.loyalty_points) - Number(a.loyalty_points);
                        }
                        if (this.sortBy === 'name_asc') {
                            return a.name.localeCompare(b.name);
                        }

                        return Number(b.id) - Number(a.id);
                    });
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

                tierBadgeClass(tier) {
                    const classes = {
                        'VIP Platinum': 'bg-purple-100 text-purple-700',
                        'Gold Member': 'bg-amber-100 text-amber-700',
                        'Regular': 'bg-emerald-100 text-emerald-700',
                        'New Client': 'bg-sky-100 text-sky-700',
                    };

                    return classes[tier] || 'bg-slate-100 text-slate-700';
                },

                openCreateModal() {
                    this.editingClientId = null;
                    Object.assign(this.form, {
                        name: '',
                        phone_number: '',
                        email: '',
                        gender: 'female',
                    });
                    this.showFormModal = true;
                },

                openEditModal(client) {
                    this.editingClientId = client.id;
                    Object.assign(this.form, {
                        name: client.name,
                        phone_number: client.raw_phone_number || '',
                        email: client.email || '',
                        gender: client.gender || 'female',
                    });
                    this.showFormModal = true;
                },
            };
        };
    </script>

    <div
        x-data="window.createClientsDirectory({
            clients: @js($clients),
            stylists: @js($stylistsList),
            openModalOnLoad: @js($openModalOnLoad),
            hasErrors: @js($errors->any()),
            oldInput: @js([
                'editing_client_id' => old('editing_client_id'),
                'name' => old('name'),
                'phone_number' => old('phone_number'),
                'email' => old('email'),
                'gender' => old('gender'),
            ]),
            storeUrl: @js(route('clients.store')),
            baseUrl: @js(url('/clients')),
        })"
        @open-client-modal.window="openCreateModal()"
        @keydown.escape.window="showFormModal = false; selectedClient = null; clientToDelete = null"
    >
        @include('clients.includes.kpi-cards')
        @include('clients.includes.toolbar-filters')
        @include('clients.includes.clients-grid')
        @include('clients.includes.clients-table')
        @include('clients.includes.client-modals')
    </div>
@endsection
