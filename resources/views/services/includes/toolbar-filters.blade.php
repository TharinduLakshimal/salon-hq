<!-- Services & Pricing Filter, Search & View Switcher Toolbar -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 mb-6 shadow-2xs flex flex-col gap-4">
    <!-- Top Row: Department Category Pills & View Mode Switcher -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-1.5">
            <button
                type="button"
                @click="categoryFilter = 'all'"
                :class="categoryFilter === 'all'
                    ? 'bg-indigo-600 border-indigo-600 text-white shadow-xs shadow-indigo-600/20'
                    : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold border transition cursor-pointer"
            >
                <span>All Departments</span>
                <span
                    :class="categoryFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'"
                    class="px-1.5 py-0.2 rounded-full text-[11px]"
                    x-text="countByCategory('all')"
                ></span>
            </button>

            <template x-for="cat in categories" :key="cat.id">
                <button
                    type="button"
                    @click="categoryFilter = cat.id"
                    :class="Number(categoryFilter) === Number(cat.id)
                        ? 'bg-indigo-600 border-indigo-600 text-white shadow-xs shadow-indigo-600/20'
                        : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold border transition cursor-pointer"
                >
                    <span x-text="cat.name"></span>
                    <span
                        :class="Number(categoryFilter) === Number(cat.id) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'"
                        class="px-1.5 py-0.2 rounded-full text-[11px]"
                        x-text="countByCategory(cat.id)"
                    ></span>
                </button>
            </template>
        </div>

        <!-- View Mode Segmented Switcher -->
        <div class="bg-slate-100 p-1 rounded-xl inline-flex items-center gap-1 self-start lg:self-auto shrink-0">
            <button
                type="button"
                @click="viewMode = 'grid'"
                :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                </svg>
                <span>Pricing Cards</span>
            </button>
            <button
                type="button"
                @click="viewMode = 'table'"
                :class="viewMode === 'table' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
                <span>Catalog Table</span>
            </button>
        </div>
    </div>

    <!-- Middle Row: Live Search, Price Tier & Sort Order -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="relative sm:col-span-2">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input
                type="text"
                x-model="search"
                placeholder="Search by service name, code (SRV-001), department, or price..."
                class="w-full pl-10 pr-9 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
            >
            <button
                type="button"
                x-show="search.length > 0"
                @click="search = ''"
                aria-label="Clear search"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer"
                style="display: none;"
            >
                ✕
            </button>
        </div>

        <div>
            <select
                x-model="priceFilter"
                aria-label="Filter by price tier"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition cursor-pointer"
            >
                <option value="all">All Price Tiers</option>
                <option value="under_1500">Under Rs. 1,500</option>
                <option value="1500_to_5000">Rs. 1,500 &ndash; Rs. 5,000</option>
                <option value="over_5000">Above Rs. 5,000</option>
            </select>
        </div>

        <div>
            <select
                x-model="sortBy"
                aria-label="Sort services"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition cursor-pointer"
            >
                <option value="category_asc">Sort: Department Menu</option>
                <option value="price_asc">Sort: Price (Low &rarr; High)</option>
                <option value="price_desc">Sort: Price (High &rarr; Low)</option>
                <option value="duration_asc">Sort: Shortest Duration</option>
                <option value="name_asc">Sort: Name (A &ndash; Z)</option>
                <option value="newest">Sort: Recently Added</option>
            </select>
        </div>
    </div>

    <!-- Bottom Row: Target Gender Segmented Filter, Package Filter & Reset -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-3 border-t border-slate-100">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Gender Segmented Control -->
            <div class="inline-flex flex-wrap items-center gap-1 bg-slate-50 border border-slate-200 rounded-lg p-0.5 text-xs">
                <button
                    type="button"
                    @click="genderFilter = 'all'"
                    :class="genderFilter === 'all' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    All Genders
                </button>
                <button
                    type="button"
                    @click="genderFilter = 'ladies'"
                    :class="genderFilter === 'ladies' ? 'bg-pink-50 text-pink-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    ♀ Ladies
                </button>
                <button
                    type="button"
                    @click="genderFilter = 'gents'"
                    :class="genderFilter === 'gents' ? 'bg-sky-50 text-sky-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    ♂ Gents
                </button>
                <button
                    type="button"
                    @click="genderFilter = 'both'"
                    :class="genderFilter === 'both' ? 'bg-purple-50 text-purple-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    ⚥ Unisex
                </button>
            </div>

            <!-- Package vs Single Segmented Control -->
            <div class="inline-flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-lg p-0.5 text-xs">
                <button
                    type="button"
                    @click="packageFilter = 'all'"
                    :class="packageFilter === 'all' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    All Types
                </button>
                <button
                    type="button"
                    @click="packageFilter = 'single'"
                    :class="packageFilter === 'single' ? 'bg-white text-indigo-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    Single
                </button>
                <button
                    type="button"
                    @click="packageFilter = 'package'"
                    :class="packageFilter === 'package' ? 'bg-amber-50 text-amber-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    ★ Packages
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between md:justify-end gap-3">
            <span class="text-xs text-slate-500">
                Showing <strong class="text-slate-800" x-text="filteredServices.length"></strong> services
            </span>
            <button
                type="button"
                x-show="hasActiveFilters"
                @click="resetFilters()"
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 cursor-pointer"
                style="display: none;"
            >
                Reset Filters
            </button>
        </div>
    </div>
</div>

