<!-- Dynamic KPI Summary Cards (Alpine.js reactive) -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Filtered Bookings</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.total">{{ count($appointments) }}</div>
            <div class="text-xs font-medium text-indigo-600">
                <span x-text="stats.confirmed"></span> confirmed &bull; <span x-text="stats.completed"></span> completed
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active in Chair</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.inProgress">2</div>
            <div class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span>Live salon sessions</span>
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="6" cy="6" r="3"></circle>
                <circle cx="6" cy="18" r="3"></circle>
                <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Approval</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5" x-text="stats.pending">1</div>
            <div class="text-xs font-medium text-amber-600">Awaiting front-desk check</div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Expected Revenue</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 my-1.5 truncate" x-text="'Rs. ' + formatCurrency(stats.revenue)">Rs. 67,500</div>
            <div class="text-xs font-medium text-emerald-600 truncate">
                Collected: Rs. <span x-text="formatCurrency(stats.completedRevenue)"></span>
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

