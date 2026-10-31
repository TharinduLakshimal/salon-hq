<!-- Dynamic Services & Pricing KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Filtered Services</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.total">{{ count($services) }}</div>
            <div class="text-xs font-medium text-slate-500 flex flex-wrap items-center gap-1.5">
                <span class="text-pink-600 font-semibold">♀ <span x-text="stats.ladiesCount"></span></span>
                <span>&bull;</span>
                <span class="text-sky-600 font-semibold">♂ <span x-text="stats.gentsCount"></span></span>
                <span x-show="stats.bothCount > 0">&bull; <span class="text-purple-600 font-semibold">⚥ <span x-text="stats.bothCount"></span></span></span>
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Salon Departments</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.activeCategoriesCount">{{ count($categoriesList) }}</div>
            <div class="text-xs font-medium text-indigo-600 truncate">Active service categories</div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Combo Packages</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.packagesCount">0</div>
            <div class="text-xs font-medium text-amber-600 truncate">
                <span x-text="stats.singleCount"></span> individual treatments
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Average Rate</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 my-1.5 truncate" x-text="'Rs. ' + formatCurrency(stats.averagePrice)">Rs. 0</div>
            <div class="text-xs font-medium text-emerald-600 truncate">
                Range: Rs. <span x-text="formatCurrency(stats.minPrice)"></span> &ndash; <span x-text="formatCurrency(stats.maxPrice)"></span>
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
    </div>
</div>

