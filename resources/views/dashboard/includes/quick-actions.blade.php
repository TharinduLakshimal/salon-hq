<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
    <div class="px-6 py-4 border-b border-slate-200">
        <h2 class="text-base font-bold text-slate-900">Quick Actions</h2>
    </div>
    <div class="p-5 grid grid-cols-2 gap-3.5">
        <a href="{{ route('appointments.index', ['new' => 1]) }}" wire:navigate
            class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="12" y1="10" x2="12" y2="16"></line>
                <line x1="9" y1="13" x2="15" y2="13"></line>
            </svg>
            <span>Add Booking</span>
        </a>
        <a href="{{ route('staff.index') }}" wire:navigate
            class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            <span>Manage Staff</span>
        </a>
        <a href="{{ route('services.index', ['new' => 1]) }}" wire:navigate
            class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            <span>Add Service</span>
        </a>
        <a href="#"
            class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
            <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            <span>POS Billing</span>
        </a>
    </div>
</div>

