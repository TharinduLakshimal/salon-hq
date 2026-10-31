@extends('layouts.app')

@section('title', 'Services & Pricing')

@section('header-left')
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl font-bold text-slate-900 truncate">Services &amp; Pricing</h1>
        <p class="hidden sm:block text-xs text-slate-500 mt-0.5 truncate">
            Manage salon menu treatments, ladies &amp; gents rates, durations, and value combo packages.
        </p>
    </div>
@endsection

@section('header-right')
    <button
        type="button"
        @click="$dispatch('open-service-modal')"
        class="inline-flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 shrink-0 whitespace-nowrap transition cursor-pointer"
    >
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Add New Service</span>
    </button>
@endsection

@section('content')
    <script>
        window.createServicesCatalog = function (config) {
            const servicesList = Array.isArray(config.services) ? config.services : [];
            const categoriesList = Array.isArray(config.categories) ? config.categories : [];
            const defaultCategoryId = categoriesList.length > 0 ? String(categoriesList[0].id) : '__new__';

            return {
                services: servicesList,
                categories: categoriesList,
                storeServiceUrl: config.storeUrl,
                baseServicesUrl: config.baseUrl,

                categoryFilter: 'all',
                genderFilter: 'all',
                packageFilter: 'all',
                priceFilter: 'all',
                sortBy: 'category_asc',
                search: '',
                viewMode: 'grid',
                currentPage: 1,
                perPage: 12,

                showFormModal: Boolean(config.openModalOnLoad || config.hasErrors),
                editingServiceId: config.oldInput.editing_service_id
                    ? Number(config.oldInput.editing_service_id)
                    : null,
                serviceToDelete: null,

                durationPresets: [15, 30, 45, 60, 90, 120, 180],

                form: {
                    name: config.oldInput.name ?? '',
                    category_id: config.oldInput.category_id
                        ? String(config.oldInput.category_id)
                        : defaultCategoryId,
                    new_category_name: config.oldInput.new_category_name ?? '',
                    target_gender: config.oldInput.target_gender || 'ladies',
                    price: config.oldInput.price ?? '',
                    duration_minutes: config.oldInput.duration_minutes ?? 30,
                    is_package:
                        config.oldInput.is_package !== null && config.oldInput.is_package !== undefined
                            ? Boolean(Number(config.oldInput.is_package))
                            : false,
                },

                init() {
                    this.$watch('categoryFilter', () => { this.currentPage = 1; });
                    this.$watch('genderFilter', () => { this.currentPage = 1; });
                    this.$watch('packageFilter', () => { this.currentPage = 1; });
                    this.$watch('priceFilter', () => { this.currentPage = 1; });
                    this.$watch('sortBy', () => { this.currentPage = 1; });
                    this.$watch('search', () => { this.currentPage = 1; });
                    this.$watch('perPage', () => { this.currentPage = 1; });
                },

                get baseFilteredWithoutCategory() {
                    const q = this.search.trim().toLowerCase();

                    return this.services.filter((service) => {
                        if (this.genderFilter !== 'all' && service.target_gender !== this.genderFilter) {
                            return false;
                        }

                        if (this.packageFilter === 'package' && !service.is_package) {
                            return false;
                        }

                        if (this.packageFilter === 'single' && service.is_package) {
                            return false;
                        }

                        const numericPrice = Number(service.price || 0);
                        if (this.priceFilter === 'under_1500' && numericPrice >= 1500) {
                            return false;
                        }
                        if (this.priceFilter === '1500_to_5000' && (numericPrice < 1500 || numericPrice > 5000)) {
                            return false;
                        }
                        if (this.priceFilter === 'over_5000' && numericPrice <= 5000) {
                            return false;
                        }

                        if (q !== '') {
                            const haystack = [
                                service.service_code,
                                service.name,
                                service.category_name,
                                this.genderLabel(service.target_gender),
                                String(service.price),
                                service.is_package ? 'package combo bundle' : 'single treatment',
                            ].join(' ').toLowerCase();

                            if (!haystack.includes(q)) {
                                return false;
                            }
                        }

                        return true;
                    });
                },

                get filteredServices() {
                    const list = this.categoryFilter === 'all'
                        ? [...this.baseFilteredWithoutCategory]
                        : this.baseFilteredWithoutCategory.filter(
                            (s) => Number(s.category_id) === Number(this.categoryFilter)
                        );

                    return list.sort((a, b) => {
                        if (this.sortBy === 'price_asc') {
                            return Number(a.price) - Number(b.price);
                        }
                        if (this.sortBy === 'price_desc') {
                            return Number(b.price) - Number(a.price);
                        }
                        if (this.sortBy === 'duration_asc') {
                            return Number(a.duration_minutes) - Number(b.duration_minutes);
                        }
                        if (this.sortBy === 'name_asc') {
                            return a.name.localeCompare(b.name);
                        }
                        if (this.sortBy === 'newest') {
                            return Number(b.id) - Number(a.id);
                        }

                        const catDiff = Number(a.category_id) - Number(b.category_id);
                        if (catDiff !== 0) {
                            return catDiff;
                        }

                        return Number(a.id) - Number(b.id);
                    });
                },

                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredServices.length / Number(this.perPage || 12)));
                },

                get safeCurrentPage() {
                    return Math.min(Math.max(1, this.currentPage), this.totalPages);
                },

                get paginatedServices() {
                    const size = Number(this.perPage || 12);
                    const start = (this.safeCurrentPage - 1) * size;

                    return this.filteredServices.slice(start, start + size);
                },

                get paginationStart() {
                    if (this.filteredServices.length === 0) {
                        return 0;
                    }

                    return (this.safeCurrentPage - 1) * Number(this.perPage || 12) + 1;
                },

                get paginationEnd() {
                    return Math.min(
                        this.safeCurrentPage * Number(this.perPage || 12),
                        this.filteredServices.length
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
                    const list = this.filteredServices;
                    const prices = list.map((s) => Number(s.price || 0));
                    const totalPrice = prices.reduce((sum, val) => sum + val, 0);

                    return {
                        total: list.length,
                        ladiesCount: list.filter((s) => s.target_gender === 'ladies').length,
                        gentsCount: list.filter((s) => s.target_gender === 'gents').length,
                        bothCount: list.filter((s) => s.target_gender === 'both').length,
                        packagesCount: list.filter((s) => s.is_package).length,
                        singleCount: list.filter((s) => !s.is_package).length,
                        activeCategoriesCount: new Set(list.map((s) => s.category_id)).size,
                        averagePrice: list.length > 0 ? Math.round(totalPrice / list.length) : 0,
                        minPrice: list.length > 0 ? Math.min(...prices) : 0,
                        maxPrice: list.length > 0 ? Math.max(...prices) : 0,
                    };
                },

                get hasActiveFilters() {
                    return (
                        this.categoryFilter !== 'all' ||
                        this.genderFilter !== 'all' ||
                        this.packageFilter !== 'all' ||
                        this.priceFilter !== 'all' ||
                        this.search.trim() !== ''
                    );
                },

                countByCategory(catKey) {
                    if (catKey === 'all') {
                        return this.baseFilteredWithoutCategory.length;
                    }

                    return this.baseFilteredWithoutCategory.filter(
                        (s) => Number(s.category_id) === Number(catKey)
                    ).length;
                },

                resetFilters() {
                    this.categoryFilter = 'all';
                    this.genderFilter = 'all';
                    this.packageFilter = 'all';
                    this.priceFilter = 'all';
                    this.search = '';
                },

                formatCurrency(amount) {
                    return Number(amount || 0).toLocaleString('en-US');
                },

                genderLabel(targetGender) {
                    const labels = {
                        ladies: 'Ladies',
                        gents: 'Gents',
                        both: 'Unisex',
                    };

                    return labels[targetGender] || 'Unisex';
                },

                genderSymbol(targetGender) {
                    const symbols = {
                        ladies: '♀',
                        gents: '♂',
                        both: '⚥',
                    };

                    return symbols[targetGender] || '⚥';
                },

                genderBadgeClass(targetGender) {
                    const classes = {
                        ladies: 'bg-pink-50 text-pink-700 border-pink-200',
                        gents: 'bg-sky-50 text-sky-700 border-sky-200',
                        both: 'bg-purple-50 text-purple-700 border-purple-200',
                    };

                    return classes[targetGender] || 'bg-slate-100 text-slate-700 border-slate-200';
                },

                categoryBadgeClass(categoryName) {
                    const normalized = String(categoryName || '').toLowerCase();

                    if (normalized.includes('barber')) {
                        return 'bg-sky-50 text-sky-700 border-sky-200';
                    }
                    if (normalized.includes('bridal') || normalized.includes('makeup')) {
                        return 'bg-rose-50 text-rose-700 border-rose-200';
                    }
                    if (normalized.includes('esthetic') || normalized.includes('nail') || normalized.includes('skin')) {
                        return 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    }

                    return 'bg-indigo-50 text-indigo-700 border-indigo-200';
                },

                selectedFormCategoryName() {
                    if (this.form.category_id === '__new__') {
                        return this.form.new_category_name.trim() || 'New Department';
                    }

                    const found = this.categories.find(
                        (cat) => Number(cat.id) === Number(this.form.category_id)
                    );

                    return found ? found.name : 'Salon Department';
                },

                openCreateModal() {
                    this.editingServiceId = null;

                    const preselectedCategory =
                        this.categoryFilter !== 'all'
                            ? String(this.categoryFilter)
                            : defaultCategoryId;
                    const preselectedGender =
                        this.genderFilter !== 'all'
                            ? this.genderFilter
                            : 'ladies';

                    Object.assign(this.form, {
                        name: '',
                        category_id: preselectedCategory,
                        new_category_name: '',
                        target_gender: preselectedGender,
                        price: '',
                        duration_minutes: 30,
                        is_package: this.packageFilter === 'package',
                    });

                    this.showFormModal = true;
                },

                openEditModal(service) {
                    this.editingServiceId = service.id;

                    Object.assign(this.form, {
                        name: service.name,
                        category_id: String(service.category_id),
                        new_category_name: '',
                        target_gender: service.target_gender || 'ladies',
                        price: Number(service.price),
                        duration_minutes: Number(service.duration_minutes || 30),
                        is_package: Boolean(service.is_package),
                    });

                    this.showFormModal = true;
                },
            };
        };
    </script>

    <div
        x-data="window.createServicesCatalog({
            services: @js($services),
            categories: @js($categoriesList),
            openModalOnLoad: @js($openModalOnLoad),
            hasErrors: @js($errors->any()),
            oldInput: @js([
                'editing_service_id' => old('editing_service_id'),
                'name' => old('name'),
                'category_id' => old('category_id'),
                'new_category_name' => old('new_category_name'),
                'target_gender' => old('target_gender'),
                'price' => old('price'),
                'duration_minutes' => old('duration_minutes'),
                'is_package' => old('is_package'),
            ]),
            storeUrl: @js(route('services.store')),
            baseUrl: @js(url('/services')),
        })"
        @open-service-modal.window="openCreateModal()"
        @keydown.escape.window="showFormModal = false; serviceToDelete = null"
    >
        @include('services.includes.kpi-cards')
        @include('services.includes.toolbar-filters')
        @include('services.includes.services-grid')
        @include('services.includes.services-table')
        @include('services.includes.service-modals')
    </div>
@endsection

