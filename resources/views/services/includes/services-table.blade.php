<!-- Services Catalog Table View -->
<div x-show="viewMode === 'table'" class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs" style="display: none;">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <h2 class="text-base font-bold text-slate-900">Salon Service &amp; Pricing Register</h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                <span x-text="filteredServices.length"></span> items
            </span>
        </div>

        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
            <div class="hidden sm:flex items-center gap-3">
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-pink-500"></span> ♀ Ladies</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sky-500"></span> ♂ Gents</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-500"></span> ⚥ Unisex</span>
            </div>

            <div class="flex items-center gap-1.5">
                <label for="services-per-page" class="text-xs font-medium text-slate-500">Rows:</label>
                <select
                    id="services-per-page"
                    x-model.number="perPage"
                    class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700 focus:bg-white focus:border-indigo-600 focus:outline-none cursor-pointer"
                >
                    <option :value="12">12 / page</option>
                    <option :value="24">24 / page</option>
                    <option :value="48">48 / page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="filteredServices.length === 0" class="p-10 text-center text-sm text-slate-500" style="display: none;">
        No salon services match your current search or filter criteria.
    </div>

    <!-- Horizontally Scrollable Table -->
    <div x-show="filteredServices.length > 0" class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                    <th class="px-4 sm:px-6 py-3.5">Service &amp; Code</th>
                    <th class="px-4 sm:px-5 py-3.5">Department</th>
                    <th class="px-4 sm:px-5 py-3.5">Target Gender</th>
                    <th class="px-4 sm:px-5 py-3.5">Type</th>
                    <th class="px-4 sm:px-5 py-3.5">Duration</th>
                    <th class="px-4 sm:px-5 py-3.5">Price (LKR)</th>
                    <th class="px-4 sm:px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                <template x-for="service in paginatedServices" :key="service.id">
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 sm:px-6 py-4">
                            <div class="font-semibold text-slate-900" x-text="service.name"></div>
                            <div class="text-[11px] font-mono text-slate-400 mt-0.5">
                                <span x-text="service.service_code"></span>
                                <span>&bull; <span x-text="service.bookings_count"></span> bookings</span>
                            </div>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <span
                                :class="categoryBadgeClass(service.category_name)"
                                class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold border"
                                x-text="service.category_name"
                            ></span>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <span
                                :class="genderBadgeClass(service.target_gender)"
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border"
                            >
                                <span x-text="genderSymbol(service.target_gender)"></span>
                                <span x-text="genderLabel(service.target_gender)"></span>
                            </span>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap">
                            <span
                                x-show="service.is_package"
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800"
                            >
                                ★ Combo Package
                            </span>
                            <span
                                x-show="!service.is_package"
                                class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600"
                            >
                                Single Service
                            </span>
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap text-slate-700 font-medium">
                            <span x-text="service.duration_minutes"></span> mins
                        </td>

                        <td class="px-4 sm:px-5 py-4 whitespace-nowrap font-bold text-indigo-700">
                            Rs. <span x-text="formatCurrency(service.price)"></span>
                        </td>

                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button
                                    type="button"
                                    @click="openEditModal(service)"
                                    class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-semibold transition cursor-pointer"
                                >
                                    Edit
                                </button>
                                <a
                                    href="{{ route('appointments.index', ['new' => 1]) }}"
                                    wire:navigate
                                    class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition"
                                >
                                    Book
                                </a>
                                @if ($isOwner)
                                    <button
                                        type="button"
                                        @click="serviceToDelete = service"
                                        title="Delete service"
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
        x-show="filteredServices.length > 0"
        class="px-4 sm:px-6 py-3.5 border-t border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
    >
        <div class="text-xs text-slate-500">
            Showing <span class="font-semibold text-slate-800" x-text="paginationStart"></span>
            to <span class="font-semibold text-slate-800" x-text="paginationEnd"></span>
            of <span class="font-semibold text-slate-800" x-text="filteredServices.length"></span> services
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

