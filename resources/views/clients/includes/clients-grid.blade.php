<!-- Clients Profile Cards Grid View -->
<div x-show="viewMode === 'grid'">
    <!-- Empty State -->
    <div x-show="filteredClients.length === 0" class="bg-white border border-slate-200 rounded-2xl p-10 sm:p-14 text-center shadow-2xs" style="display: none;">
        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-900">No clients match your current filters</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Try clearing your search filters or register a new walk-in or regular salon client.</p>
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
                + Add New Client
            </button>
        </div>
    </div>

    <!-- Responsive Cards Grid -->
    <div x-show="filteredClients.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <template x-for="client in filteredClients" :key="client.id">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col justify-between gap-4 shadow-2xs hover:border-indigo-300 hover:shadow-sm transition">
                <div class="space-y-3.5">
                    <!-- Top Identity Row -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-12 h-12 rounded-2xl text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm"
                                :style="'background: ' + client.avatar_gradient"
                                x-text="client.avatar_initials"
                            ></div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <h3 class="text-base font-bold text-slate-900 truncate" x-text="client.name"></h3>
                                    <span
                                        :class="client.gender === 'female' ? 'bg-pink-50 text-pink-700' : 'bg-sky-50 text-sky-700'"
                                        class="inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-bold shrink-0"
                                        x-text="client.gender === 'female' ? '♀' : '♂'"
                                    ></span>
                                </div>
                                <div class="text-[11px] font-mono font-semibold text-slate-400 mt-0.5" x-text="client.client_code"></div>
                            </div>
                        </div>

                        <span
                            :class="tierBadgeClass(client.membership_tier)"
                            class="px-2.5 py-1 rounded-full text-[11px] font-semibold shrink-0"
                            x-text="client.membership_tier"
                        ></span>
                    </div>

                    <!-- Contact & Stylist Info -->
                    <div class="space-y-1.5 text-xs text-slate-600">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-medium text-slate-800" x-text="client.phone_number"></span>
                            <span class="text-slate-400 truncate" x-text="client.address || 'Colombo'"></span>
                        </div>
                        <div class="text-slate-500 truncate" x-text="client.email || 'No email on file'"></div>
                        <div class="pt-1 flex items-center justify-between gap-2 text-xs">
                            <span class="text-slate-400">Preferred Stylist:</span>
                            <span class="font-semibold text-indigo-700 truncate" x-text="client.preferred_stylist"></span>
                        </div>
                    </div>

                    <!-- Hair / Skin Formula & Sensitivity Note -->
                    <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Formula & Care Notes</div>
                        <p class="text-xs text-slate-600 line-clamp-2" x-text="client.hair_skin_notes || 'No specific allergies or hair/skin formulas recorded.'"></p>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <!-- 3-Column Metrics Strip -->
                    <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 text-center">
                        <div class="rounded-xl bg-slate-50/80 py-2 px-1">
                            <div class="text-sm font-bold text-slate-900" x-text="client.total_visits"></div>
                            <div class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Visits</div>
                        </div>
                        <div class="rounded-xl bg-amber-50/70 py-2 px-1">
                            <div class="text-sm font-bold text-amber-700" x-text="formatNumber(client.loyalty_points)"></div>
                            <div class="text-[10px] font-semibold uppercase tracking-wide text-amber-600/80">Points</div>
                        </div>
                        <div class="rounded-xl bg-emerald-50/70 py-2 px-1">
                            <div class="text-xs sm:text-sm font-bold text-emerald-700 truncate" x-text="'Rs. ' + formatCompactSpend(client.total_spent)"></div>
                            <div class="text-[10px] font-semibold uppercase tracking-wide text-emerald-600/80">Spent</div>
                        </div>
                    </div>

                    <!-- Card Actions Footer -->
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="selectedClient = client"
                            class="flex-1 py-2 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition cursor-pointer"
                        >
                            View Profile
                        </button>
                        <a
                            href="{{ route('appointments.index', ['new' => 1]) }}"
                            wire:navigate
                            class="flex-1 py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white text-center transition"
                        >
                            Book Slot
                        </a>
                        <button
                            type="button"
                            @click="openEditModal(client)"
                            title="Edit client profile"
                            aria-label="Edit client profile"
                            class="w-8.5 h-8.5 rounded-xl border border-slate-200 bg-white hover:bg-indigo-50 hover:border-indigo-200 text-slate-600 hover:text-indigo-600 flex items-center justify-center shrink-0 transition cursor-pointer"
                        >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        @if ($isOwner)
                            <button
                                type="button"
                                @click="clientToDelete = client"
                                title="Delete client"
                                aria-label="Delete client"
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
</div>

