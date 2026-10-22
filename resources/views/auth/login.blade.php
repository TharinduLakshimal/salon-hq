<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Salon HQ') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-slate-200/80 p-8">
        <div class="text-center mb-7">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center mx-auto mb-3 shadow-md shadow-indigo-500/30">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="6" r="3"></circle>
                    <circle cx="6" cy="18" r="3"></circle>
                    <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                    <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                    <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Salon HQ</h1>
            <p class="text-sm text-slate-500 mt-1">Sign in to your admin account</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600 font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="flex flex-col gap-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autofocus
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                >
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-3 px-4 shadow-md shadow-indigo-600/20 transition cursor-pointer"
            >
                Sign In
            </button>
        </form>
    </div>
</body>
</html>
