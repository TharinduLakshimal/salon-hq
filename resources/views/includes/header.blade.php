<!-- Top Header -->
<header class="bg-white border-b border-slate-200 px-6 lg:px-8 py-4 flex items-center justify-between gap-4 sticky top-0 z-40">
    <div class="flex items-center gap-4">
        <button
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            class="md:hidden inline-flex items-center justify-center p-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 cursor-pointer"
        >
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        @yield('header-left')
    </div>

    <div class="flex items-center gap-3">
        @yield('header-right')
    </div>
</header>

