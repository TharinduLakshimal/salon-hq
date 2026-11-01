@section('title', 'Booking Confirmed - Salon HQ')
<!DOCTYPE html>
<html lang="en">
@include('includes.head')
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased flex flex-col justify-between">

    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                @include('includes.brand-logo', [
                    'wrapperClass' => 'w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-sm shadow-indigo-500/20',
                    'iconClass' => 'w-5 h-5',
                ])
                <div>
                    <span class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition">Salon HQ</span>
                    <span class="block text-[11px] font-medium text-slate-400">Appointment Confirmation</span>
                </div>
            </a>

            <a href="{{ route('booking.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition">
                + New Booking
            </a>
        </div>
    </header>

    <!-- Success Confirmation Container -->
    <main class="flex-1 max-w-xl w-full mx-auto px-4 py-10 sm:py-14">
        @if (session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-emerald-800 font-semibold text-center shadow-2xs">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-10 shadow-sm text-center space-y-6">
            <!-- Animated Success Icon -->
            <div class="w-16 h-16 rounded-3xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-md shadow-emerald-500/20">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 mb-2">
                    Booking Confirmed
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">You're All Set!</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Your appointment has been registered in the Salon HQ scheduling system.
                </p>
            </div>

            @if ($appointment)
                <!-- Booking Reference Code Badge -->
                <div class="rounded-2xl bg-slate-50 border border-dashed border-slate-300 p-4 inline-block w-full">
                    <div class="text-[11px] uppercase tracking-wider font-bold text-slate-400">Booking Reference Code</div>
                    <div class="text-2xl font-mono font-bold text-indigo-600 mt-1 tracking-wide">
                        {{ $appointment->booking_code }}
                    </div>
                </div>

                <!-- Structured Appointment Details Card -->
                <div class="rounded-2xl border border-slate-200 text-left p-5 space-y-3.5 text-xs text-slate-600 bg-slate-50/50">
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/70">
                        <span class="text-slate-400 font-medium">Service</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $appointment->service_name }}</span>
                    </div>

                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/70">
                        <span class="text-slate-400 font-medium">Stylist</span>
                        <span class="font-semibold text-slate-800">
                            {{ $appointment->staff?->user?->name ?? 'Assigned Stylist' }}
                            @if ($appointment->category)
                                <span class="text-slate-400 font-normal">({{ $appointment->category->name }})</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/70">
                        <span class="text-slate-400 font-medium">Date & Time</span>
                        <span class="font-semibold text-slate-800">
                            {{ $appointment->appointment_date?->format('l, M d, Y') }} at {{ Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/70">
                        <span class="text-slate-400 font-medium">Client</span>
                        <span class="font-semibold text-slate-800">
                            {{ $appointment->client_name }} ({{ $appointment->client_phone }})
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <span class="text-slate-400 font-medium">Total Amount Due at Salon</span>
                        <span class="font-bold text-emerald-700 text-sm">
                            Rs. {{ number_format((float) $appointment->price, 2) }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="text-xs text-slate-500 bg-slate-50 rounded-2xl p-4 border border-slate-200/70 leading-relaxed text-left">
                📍 <strong>Salon HQ Location:</strong> 123 Galle Road, Colombo 03.<br>
                📞 Need to reschedule or have questions? Contact our front desk at <strong>(011) 234-5678</strong>.
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a
                    href="{{ route('booking.index') }}"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition"
                >
                    + Book Another Service
                </a>
                <a
                    href="{{ url('/') }}"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs transition"
                >
                    Back to Home
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        <div class="max-w-4xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Salon HQ. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

