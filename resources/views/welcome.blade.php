@section('title', 'Welcome')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('includes.head')
<body class="min-h-screen bg-slate-100 text-slate-900 flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-slate-200/80 p-8 text-center">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center mx-auto mb-5 shadow-md shadow-indigo-500/30">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="6" cy="6" r="3"></circle>
                <circle cx="6" cy="18" r="3"></circle>
                <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
            </svg>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 mb-3">
            Welcome
        </span>

        <h1 class="text-2xl font-bold text-slate-900">Welcome to Salon HQ</h1>
        <p class="text-sm text-slate-500 mt-2 mb-7">
            Unisex Salon Management System for managing appointments, stylists, and daily operations.
        </p>

        <div class="flex flex-col gap-3">
            @auth
                <a
                    href="{{ route('dashboard') }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-3 px-4 shadow-md shadow-indigo-600/20 transition"
                >
                    <span>Go to Dashboard</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-3 px-4 shadow-md shadow-indigo-600/20 transition"
                >
                    <span>Login to Salon HQ</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
