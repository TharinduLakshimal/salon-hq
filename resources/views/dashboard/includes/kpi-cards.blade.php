<!-- KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Bookings</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">18</div>
            <div class="text-xs font-medium text-emerald-600">+14% vs yesterday</div>
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
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Revenue</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">Rs. 48,500</div>
            <div class="text-xs font-medium text-emerald-600">+8.2% vs last week</div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active Clients</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">342</div>
            <div class="text-xs font-medium text-emerald-600">+24 new this month</div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Staff on Duty</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">{{ $staffMembers->count() }}</div>
            <div class="text-xs font-medium text-slate-500">Unisex salon team</div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="6" cy="6" r="3"></circle>
                <circle cx="6" cy="18" r="3"></circle>
                <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
            </svg>
        </div>
    </div>
</div>

