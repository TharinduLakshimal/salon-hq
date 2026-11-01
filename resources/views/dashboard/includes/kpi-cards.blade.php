<!-- KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Bookings</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">{{ $todayAppointmentsCount }}</div>
            <div class="text-xs font-medium text-indigo-600">
                {{ $todayCompletedAppointmentsCount }} completed today
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
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Revenue</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">Rs. {{ number_format($todayExpectedRevenue, 0) }}</div>
            <div class="text-xs font-medium text-emerald-600">Expected total earnings</div>
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
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Completed Sessions</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">{{ $todayCompletedAppointmentsCount }}</div>
            <div class="text-xs font-medium text-amber-600">
                @if ($todayAppointmentsCount > 0)
                    {{ round(($todayCompletedAppointmentsCount / $todayAppointmentsCount) * 100) }}% completion rate
                @else
                    No sessions scheduled yet
                @endif
            </div>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Staff on Duty</div>
            <div class="text-3xl font-bold text-slate-900 my-1.5">{{ $staffMembers->count() }}</div>
            <div class="text-xs font-medium text-slate-500">{{ $availableStaffCount }} available right now</div>
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
