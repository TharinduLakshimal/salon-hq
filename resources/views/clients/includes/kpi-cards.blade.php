<!-- Dynamic Client CRM KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Filtered Clients</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.total">{{ count($clients) }}</div>
            <div class="text-xs font-medium text-slate-500">
                <span class="text-pink-600 font-semibold">♀ <span x-text="stats.femaleCount"></span></span>
                &bull;
                <span class="text-sky-600 font-semibold">♂ <span x-text="stats.maleCount"></span></span>
                unisex profiles
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">VIP & Gold Tier</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.vipAndGold">4</div>
            <div class="text-xs font-medium text-purple-600">High-retention members</div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Loyalty Points</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 my-1.5 truncate">
                <span x-text="formatNumber(stats.totalPoints)">8,180</span> <span class="text-sm font-semibold text-slate-400">pts</span>
            </div>
            <div class="text-xs font-medium text-amber-600">Active reward balance</div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="8" r="7"></circle>
                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Lifetime Spend</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 my-1.5 truncate" x-text="'Rs. ' + formatNumber(stats.totalSpend)">Rs. 490,000</div>
            <div class="text-xs font-medium text-emerald-600 truncate">
                Avg: Rs. <span x-text="formatNumber(stats.averageSpend)"></span> / client
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

