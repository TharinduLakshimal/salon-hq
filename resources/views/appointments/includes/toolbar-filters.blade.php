<!-- Appointments Filter & View Switcher Toolbar -->
<div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 mb-6 shadow-2xs flex flex-col gap-4">
    <!-- Top Row: Date Switcher & View Toggle -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <button
                type="button"
                @click="dateFilter = 'all'; customDate = ''"
                :class="dateFilter === 'all' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
                class="px-3.5 py-2 rounded-xl text-xs font-semibold border transition cursor-pointer"
            >
                All Dates
            </button>
            <button
                type="button"
                @click="dateFilter = todayDate; customDate = ''"
                :class="dateFilter === todayDate && customDate === '' ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs shadow-indigo-600/20' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
                class="px-3.5 py-2 rounded-xl text-xs font-semibold border transition cursor-pointer"
            >
                Today ({{ \Carbon\Carbon::parse($today)->format('M d') }})
            </button>
            <button
                type="button"
                @click="dateFilter = tomorrowDate; customDate = ''"
                :class="dateFilter === tomorrowDate && customDate === '' ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs shadow-indigo-600/20' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
                class="px-3.5 py-2 rounded-xl text-xs font-semibold border transition cursor-pointer"
            >
                Tomorrow ({{ \Carbon\Carbon::parse($tomorrow)->format('M d') }})
            </button>

            <div class="relative w-full sm:w-auto">
                <div
                    :class="customDate !== '' && dateFilter === customDate
                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs shadow-indigo-600/20'
                        : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300 hover:bg-white'"
                    class="w-full sm:w-auto px-3.5 py-2 rounded-xl text-xs font-semibold border flex items-center justify-between sm:justify-start gap-2 transition"
                >
                    <span class="inline-flex items-center gap-2 min-w-0">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="truncate" x-text="customDate ? formatCustomDateLabel(customDate) : 'Select Custom Date'">Select Custom Date</span>
                    </span>
                    <svg class="w-3.5 h-3.5 shrink-0 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <input
                    type="date"
                    x-model="customDate"
                    @click="if ($el.showPicker) { try { $el.showPicker(); } catch (e) {} }"
                    @change="dateFilter = customDate || 'all'"
                    aria-label="Filter by specific date"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                >
            </div>
        </div>

        <!-- View Mode Segmented Switcher -->
        <div class="bg-slate-100 p-1 rounded-xl inline-flex items-center gap-1 self-start lg:self-auto">
            <button
                type="button"
                @click="viewMode = 'list'"
                :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
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
                <span>Bookings List</span>
            </button>
            <button
                type="button"
                @click="viewMode = 'timeline'"
                :class="viewMode === 'timeline' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Chair Schedule</span>
            </button>
        </div>
    </div>

    <!-- Middle Row: Search, Stylist & Department Filters -->
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ $isWorker ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }} gap-3">
        <div class="relative {{ $isWorker ? 'sm:col-span-2 lg:col-span-2' : 'sm:col-span-2' }}">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input
                type="text"
                x-model="search"
                placeholder="Search by client name, phone, booking ID, or service..."
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

        @if (! $isWorker)
            <div>
                <select
                    x-model="stylistFilter"
                    aria-label="Filter by stylist"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition cursor-pointer"
                >
                    <option value="all">All Stylists & Barbers</option>
                    @foreach ($stylistsList as $stylist)
                        <option value="{{ $stylist['name'] }}">{{ $stylist['name'] }} ({{ $stylist['category'] }})</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <select
                x-model="categoryFilter"
                aria-label="Filter by salon department"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition cursor-pointer"
            >
                <option value="all">All Salon Departments</option>
                <option value="Bridal & Makeup">Bridal & Makeup</option>
                <option value="Hair Stylists">Hair Stylists</option>
                <option value="Barbers">Barbers (Gents)</option>
                <option value="Estheticians & Nails">Estheticians & Nails</option>
            </select>
        </div>
    </div>

    <!-- Bottom Row: Status Filter Pills & Gender Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100">
        <div class="flex flex-wrap items-center gap-1.5">
            <template x-for="tab in statusTabs" :key="tab.key">
                <button
                    type="button"
                    @click="statusFilter = tab.key"
                    :class="statusFilter === tab.key
                        ? 'bg-indigo-600 border-indigo-600 text-white'
                        : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition cursor-pointer"
                >
                    <span x-text="tab.label"></span>
                    <span
                        :class="statusFilter === tab.key ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'"
                        class="px-1.5 py-0.2 rounded-full text-[11px]"
                        x-text="countByStatus(tab.key)"
                    ></span>
                </button>
            </template>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-lg p-0.5 text-xs">
                <button
                    type="button"
                    @click="genderFilter = 'all'"
                    :class="genderFilter === 'all' ? 'bg-white text-slate-900 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    ⚥ All
                </button>
                <button
                    type="button"
                    @click="genderFilter = 'female'"
                    :class="genderFilter === 'female' ? 'bg-pink-50 text-pink-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    ♀ Ladies
                </button>
                <button
                    type="button"
                    @click="genderFilter = 'male'"
                    :class="genderFilter === 'male' ? 'bg-sky-50 text-sky-700 font-semibold shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded-md transition cursor-pointer"
                >
                    ♂ Gents
                </button>
            </div>

            <button
                type="button"
                x-show="hasActiveFilters"
                @click="resetFilters()"
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2 py-1 cursor-pointer"
                style="display: none;"
            >
                Reset Filters
            </button>
        </div>
    </div>
</div>

