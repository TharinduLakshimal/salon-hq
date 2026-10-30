<!-- Clients Filter, Search & View Switcher Toolbar -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 mb-6 shadow-2xs flex flex-col gap-4">
    <!-- Top Row: Membership Tier Pills & View Switcher -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-1.5">
            <template x-for="tab in tierTabs" :key="tab.key">
                <button
                    type="button"
                    @click="tierFilter = tab.key"
                    :class="tierFilter === tab.key
                        ? 'bg-indigo-600 border-indigo-600 text-white shadow-xs shadow-indigo-600/20'
                        : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold border transition cursor-pointer"
                >
                    <span x-text="tab.label"></span>
                    <span
                        :class="tierFilter === tab.key ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'"
                        class="px-1.5 py-0.2 rounded-full text-[11px]"
                        x-text="countByTier(tab.key)"
                    ></span>
                </button>
            </template>
        </div>

        <!-- View Mode Segmented Switcher -->
        <div class="bg-slate-100 p-1 rounded-xl inline-flex items-center gap-1 self-start lg:self-auto">
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
                <span>Profile Cards</span>
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
                <span>Directory Table</span>
            </button>
        </div>
    </div>

    <!-- Middle Row: Live Search, Stylist & Sort -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="relative sm:col-span-2">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input
                type="text"
                x-model="search"
                placeholder="Search by client name, mobile, email, client ID, or service..."
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
                x-model="stylistFilter"
                aria-label="Filter by stylist"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition cursor-pointer"
            >
                <option value="all">All Stylists & Clients</option>
                @foreach ($stylistsList as $stylist)
                    <option value="{{ $stylist['name'] }}">{{ $stylist['name'] }}</option>
                @endforeach
                <option value="Any Available Stylist">No Stylist Yet (New Clients)</option>
            </select>
        </div>

        <div>
            <select
                x-model="sortBy"
                aria-label="Sort clients"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition cursor-pointer"
            >
                <option value="newest">Sort: Newest Registered</option>
                <option value="spend_desc">Sort: Highest Spend</option>
                <option value="visits_desc">Sort: Most Visits</option>
                <option value="points_desc">Sort: Most Loyalty Points</option>
                <option value="name_asc">Sort: Name (A &ndash; Z)</option>
            </select>
        </div>
    </div>

    <!-- Bottom Row: Unisex Gender Filter & Reset -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mr-1">Client Gender:</span>
            <div class="inline-flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-lg p-0.5 text-xs">
                <button
                    type="button"
                    @click="genderFilter = 'all'"
                    :class="genderFilter === 'all' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-3 py-1 rounded-md transition cursor-pointer"
                >
                    ⚥ All Clients
                </button>
                <button
                    type="button"
                    @click="genderFilter = 'female'"
                    :class="genderFilter === 'female' ? 'bg-pink-50 text-pink-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-3 py-1 rounded-md transition cursor-pointer"
                >
                    ♀ Ladies
                </button>
                <button
                    type="button"
                    @click="genderFilter = 'male'"
                    :class="genderFilter === 'male' ? 'bg-sky-50 text-sky-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-3 py-1 rounded-md transition cursor-pointer"
                >
                    ♂ Gents
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3">
            <span class="text-xs text-slate-500">
                Showing <strong class="text-slate-800" x-text="filteredClients.length"></strong> database clients
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
