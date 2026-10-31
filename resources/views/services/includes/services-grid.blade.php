<!-- Services Pricing Cards Grid View -->
<div x-show="viewMode === 'grid'">
    <!-- Empty State -->
    <div
        x-show="filteredServices.length === 0"
        class="bg-white border border-slate-200 rounded-2xl p-10 sm:p-14 text-center shadow-2xs"
        style="display: none;"
    >
        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-900">No salon services match your filters</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
            Try resetting your category, gender, or price filters, or add a new salon service to the catalog.
        </p>
        <div class="mt-4 flex flex-wrap items-center justify-center gap-2.5">
            <button
                type="button"
                @click="resetFilters()"
                class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 cursor-pointer"
            >
                Reset Filters
            </button>
            <button
                type="button"
                @click="openCreateModal()"
                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white cursor-pointer"
            >
                + Add New Service
            </button>
        </div>
    </div>

    <!-- Responsive Pricing Cards Grid -->
    <div x-show="filteredServices.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <template x-for="service in paginatedServices" :key="service.id">
            <div
                :class="service.is_package ? 'border-amber-200 bg-linear-to-b from-amber-50/35 to-white' : 'border-slate-200 bg-white'"
                class="border rounded-2xl p-5 flex flex-col justify-between gap-4 shadow-2xs hover:border-indigo-300 hover:shadow-sm transition"
            >
                <div class="space-y-3">
                    <!-- Top Badges Row -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex flex-wrap items-center gap-1.5 min-w-0">
                            <span
                                :class="categoryBadgeClass(service.category_name)"
                                class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border"
                                x-text="service.category_name"
                            ></span>
                            <span
                                x-show="service.is_package"
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200"
                            >
                                ★ Combo Package
                            </span>
                        </div>

                        <span
                            :class="genderBadgeClass(service.target_gender)"
                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border shrink-0"
                        >
                            <span x-text="genderSymbol(service.target_gender)"></span>
                            <span x-text="genderLabel(service.target_gender)"></span>
                        </span>
                    </div>

                    <!-- Service Title & Code -->
                    <div>
                        <h3 class="text-base font-bold text-slate-900 leading-snug" x-text="service.name"></h3>
                        <div class="text-[11px] font-mono font-semibold text-slate-400 mt-0.5" x-text="service.service_code"></div>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <!-- Price & Duration Strip -->
                    <div class="rounded-xl bg-slate-50 border border-slate-200/80 px-3.5 py-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Salon Price</div>
                            <div class="text-lg font-extrabold text-indigo-700 leading-tight mt-0.5">
                                Rs. <span x-text="formatCurrency(service.price)"></span>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 bg-white border border-slate-200 px-2.5 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span x-text="service.duration_minutes + ' mins'"></span>
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1">
                                <span x-text="service.bookings_count"></span> active bookings
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Footer -->
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="openEditModal(service)"
                            class="flex-1 py-2 px-3 rounded-xl border border-slate-200 bg-white hover:bg-indigo-50 hover:border-indigo-200 text-xs font-semibold text-slate-700 hover:text-indigo-700 inline-flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <span>Edit Price &amp; Info</span>
                        </button>

                        <a
                            href="{{ route('appointments.index', ['new' => 1]) }}"
                            wire:navigate
                            class="py-2 px-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white text-center transition shrink-0"
                        >
                            Book
                        </a>

                        @if ($isOwner)
                            <button
                                type="button"
                                @click="serviceToDelete = service"
                                title="Delete service"
                                aria-label="Delete service"
                                class="w-8.5 h-8.5 rounded-xl border border-red-200 bg-red-50/50 hover:bg-red-600 text-red-600 hover:text-white flex items-center justify-center shrink-0 transition cursor-pointer"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Grid Pagination Bar -->
    <div
        x-show="filteredServices.length > 0 && totalPages > 1"
        class="mt-6 bg-white border border-slate-200 rounded-2xl px-4 sm:px-6 py-3.5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3"
    >
        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
            <span>
                Showing <strong class="text-slate-800" x-text="paginationStart"></strong>
                to <strong class="text-slate-800" x-text="paginationEnd"></strong>
                of <strong class="text-slate-800" x-text="filteredServices.length"></strong> services
            </span>

            <select
                x-model.number="perPage"
                aria-label="Cards per page"
                class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700 focus:bg-white focus:border-indigo-600 focus:outline-none cursor-pointer"
            >
                <option :value="12">12 / page</option>
                <option :value="24">24 / page</option>
                <option :value="48">48 / page</option>
            </select>
        </div>

        <div class="flex flex-wrap items-center gap-1.5">
            <button
                type="button"
                @click="goToPage(safeCurrentPage - 1)"
                :disabled="safeCurrentPage <= 1"
                :class="safeCurrentPage <= 1
                    ? 'opacity-45 cursor-not-allowed bg-white text-slate-400 border-slate-200'
                    : 'bg-white hover:bg-slate-100 text-slate-700 border-slate-200 cursor-pointer'"
                class="px-3 py-1.5 rounded-lg border text-xs font-semibold transition"
            >
                &larr; Prev
            </button>

            <template x-for="(item, idx) in visiblePages" :key="idx">
                <div>
                    <span
                        x-show="item === '...'"
                        class="px-2 py-1 text-xs font-semibold text-slate-400 select-none"
                    >
                        &hellip;
                    </span>
                    <button
                        type="button"
                        x-show="item !== '...'"
                        @click="goToPage(item)"
                        :class="item === safeCurrentPage
                            ? 'bg-indigo-600 border-indigo-600 text-white shadow-2xs'
                            : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                        class="min-w-8 h-8 px-2.5 rounded-lg border text-xs font-semibold flex items-center justify-center transition cursor-pointer"
                        x-text="item"
                    ></button>
                </div>
            </template>

            <button
                type="button"
                @click="goToPage(safeCurrentPage + 1)"
                :disabled="safeCurrentPage >= totalPages"
                :class="safeCurrentPage >= totalPages
                    ? 'opacity-45 cursor-not-allowed bg-white text-slate-400 border-slate-200'
                    : 'bg-white hover:bg-slate-100 text-slate-700 border-slate-200 cursor-pointer'"
                class="px-3 py-1.5 rounded-lg border text-xs font-semibold transition"
            >
                Next &rarr;
            </button>
        </div>
    </div>
</div>

