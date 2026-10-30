<!-- Clients Directory Table View -->
<div x-show="viewMode === 'table'" class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs" style="display: none;">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <h2 class="text-base font-bold text-slate-900">Client CRM Directory</h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                <span x-text="filteredClients.length"></span> clients
            </span>
        </div>

        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
            <div class="hidden sm:flex items-center gap-3">
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-pink-500"></span> ♀ Ladies</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sky-500"></span> ♂ Gents</span>
            </div>

            <div class="flex items-center gap-1.5">
                <label for="clients-per-page" class="text-xs font-medium text-slate-500">Rows:</label>
                <select
                    id="clients-per-page"
                    x-model.number="perPage"
                    class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700 focus:bg-white focus:border-indigo-600 focus:outline-none cursor-pointer"
                >
                    <option :value="5">5 / page</option>
                    <option :value="10">10 / page</option>
                    <option :value="25">25 / page</option>
                    <option :value="50">50 / page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="filteredClients.length === 0" class="p-10 text-center text-sm text-slate-500" style="display: none;">
        No client records match your current search or filter criteria.
    </div>

    <!-- Horizontally Scrollable Table -->
    <div x-show="filteredClients.length > 0" class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                    <th class="px-4 sm:px-6 py-3.5">Client</th>
                    <th class="px-4 sm:px-5 py-3.5">Contact & Email</th>
                    <th class="px-4 sm:px-5 py-3.5">Tier & Loyalty</th>
                    <th class="px-4 sm:px-5 py-3.5">Last Stylist</th>
                    <th class="px-4 sm:px-5 py-3.5">Visits</th>
                    <th class="px-4 sm:px-5 py-3.5">Lifetime Spend</th>
                    <th class="px-4 sm:px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                <template x-for="client in paginatedTableClients" :key="client.id">
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-xl text-white text-xs font-bold flex items-center justify-center shrink-0"
                                    :style="'background: ' + client.avatar_gradient"
                                    x-text="client.avatar_initials"
                                ></div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-semibold text-slate-900" x-text="client.name"></span>
                                        <span
                                            :class="client.gender === 'female' ? 'bg-pink-50 text-pink-700' : 'bg-sky-50 text-sky-700'"
                                            class="inline-flex items-center justify-center w-4.5 h-4.5 rounded-full text-[11px] font-bold"
                                            x-text="client.gender === 'female' ? '♀' : '♂'"
                                        ></span>
                                    </div>
                                    <div class="text-[11px] font-mono text-slate-400" x-text="client.client_code"></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-800" x-text="client.phone_number"></div>
                            <div class="text-xs text-slate-500">
                                <span x-text="client.email"></span>
                                <span>&bull; Joined <span x-text="client.joined_date"></span></span>
                            </div>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <span
                                :class="tierBadgeClass(client.membership_tier)"
                                class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                x-text="client.membership_tier"
                            ></span>
                            <div class="text-xs font-semibold text-amber-600 mt-1">
                                <span x-text="formatNumber(client.loyalty_points)"></span> pts
                            </div>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap font-medium text-slate-700" x-text="client.preferred_stylist"></td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <span class="font-bold text-slate-900" x-text="client.total_visits"></span>
                            <span class="text-xs text-slate-400">visits</span>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap font-bold text-slate-900">
                            Rs. <span x-text="formatNumber(client.total_spent)"></span>
                        </td>

                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button
                                    type="button"
                                    @click="selectedClient = client"
                                    class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 transition cursor-pointer"
                                >
                                    Profile
                                </button>
                                <button
                                    type="button"
                                    @click="openEditModal(client)"
                                    class="px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold transition cursor-pointer"
                                >
                                    Edit
                                </button>
                                <a
                                    :href="bookingUrlForClient(client)"
                                    wire:navigate
                                    class="px-2.5 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition"
                                >
                                    Book
                                </a>
                                @if ($isOwner)
                                    <button
                                        type="button"
                                        @click="clientToDelete = client"
                                        title="Delete client"
                                        class="w-7.5 h-7.5 rounded-lg border border-red-200 bg-red-50/60 hover:bg-red-600 text-red-600 hover:text-white text-xs font-bold flex items-center justify-center transition cursor-pointer"
                                    >
                                        ✕
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Responsive Pagination Footer -->
    <div
        x-show="filteredClients.length > 0"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
    >
        <div class="text-xs text-slate-500">
            Showing <span class="font-semibold text-slate-800" x-text="paginationStart"></span>
            to <span class="font-semibold text-slate-800" x-text="paginationEnd"></span>
            of <span class="font-semibold text-slate-800" x-text="filteredClients.length"></span> clients
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
