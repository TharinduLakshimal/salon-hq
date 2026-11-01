@extends('layouts.app')

@section('title', $client->name . ' - Client CRM Profile')

@section('header-left')
    <a href="{{ route('clients.index') }}" wire:navigate class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
        &larr; Back to Clients
    </a>
@endsection

@section('header-right')
    <a
        href="{{ route('appointments.index', ['new' => 1, 'client_id' => $client->id]) }}"
        wire:navigate
        class="inline-flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 shrink-0 whitespace-nowrap transition cursor-pointer"
    >
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Book Appointment</span>
    </a>
@endsection

@section('content')
    @php
        $nameParts = preg_split('/\s+/', trim($client->name)) ?: [];
        $initials = '';
        foreach (array_slice($nameParts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        $initials = $initials ?: 'CL';

        $year = $client->created_at?->format('Y') ?? now()->format('Y');
        $clientCode = sprintf('CL-%s-%04d', $year, $client->id);

        $gradients = [
            'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
            'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
            'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
            'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)',
        ];
        $avatarGradient = $gradients[$client->id % count($gradients)];

        $tierBadgeClasses = match ($membershipTier) {
            'VIP Platinum' => 'bg-purple-100 text-purple-700',
            'Gold Member' => 'bg-amber-100 text-amber-700',
            'Regular' => 'bg-emerald-100 text-emerald-700',
            default => 'bg-sky-100 text-sky-700',
        };

        $latestAppointment = $client->appointments->first();
        $preferredStylist = $latestAppointment?->staff?->user?->name ?? 'Any Available Stylist';
    @endphp

    <!-- Client Hero Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 mb-6 grid grid-cols-1 lg:grid-cols-3 gap-8 shadow-2xs">
        <div class="lg:col-span-2 flex flex-col sm:flex-row items-start gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shrink-0 shadow-md" style="background: {{ $avatarGradient }}">
                {{ $initials }}
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2.5">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $client->name }}</h1>
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-bold {{ $client->gender === 'female' ? 'bg-pink-50 text-pink-700' : 'bg-sky-50 text-sky-700' }}">
                        {{ $client->gender === 'female' ? '♀' : '♂' }}
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $tierBadgeClasses }}">
                        {{ $membershipTier }}
                    </span>
                </div>

                <div class="font-mono text-xs font-semibold text-slate-400 mt-1">
                    {{ $clientCode }} &bull; Client Account
                </div>

                <div class="mt-4 flex flex-wrap gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span>{{ $client->phone_number ?: 'No phone provided' }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span>{{ $client->email }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Metadata Sidebar Box -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex flex-col gap-3 text-xs">
            <div class="flex justify-between pb-2.5 border-b border-dashed border-slate-200">
                <span class="text-slate-500 font-medium">Preferred Stylist</span>
                <span class="font-semibold text-indigo-700">{{ $preferredStylist }}</span>
            </div>
            <div class="flex justify-between pb-2.5 border-b border-dashed border-slate-200">
                <span class="text-slate-500 font-medium">Client Since</span>
                <span class="font-semibold text-slate-900">{{ $client->created_at?->format('M d, Y') ?? 'Recently' }}</span>
            </div>
            <div class="flex justify-between pb-2.5 border-b border-dashed border-slate-200">
                <span class="text-slate-500 font-medium">Gender Preference</span>
                <span class="font-semibold text-slate-900 capitalize">{{ $client->gender ?: 'Unspecified' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 font-medium">Account Status</span>
                <span class="font-semibold text-emerald-700">Active</span>
            </div>
        </div>
    </div>

    <!-- Client CRM KPI Stats Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Completed Visits</div>
            <div class="text-2xl font-bold text-slate-900 mt-1.5">{{ $totalVisits }}</div>
            <div class="text-xs font-medium text-slate-400 mt-0.5">Recorded salon visits</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Lifetime Spend</div>
            <div class="text-2xl font-bold text-emerald-600 mt-1.5">Rs. {{ number_format($totalSpend, 2) }}</div>
            <div class="text-xs font-medium text-emerald-600 mt-0.5">Total completed revenue</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Loyalty Points</div>
            <div class="text-2xl font-bold text-amber-600 mt-1.5">{{ number_format($loyaltyPoints) }} pts</div>
            <div class="text-xs font-medium text-amber-600 mt-0.5">Earned from bookings</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Membership Tier</div>
            <div class="text-2xl font-bold text-indigo-600 mt-1.5">{{ $membershipTier }}</div>
            <div class="text-xs font-medium text-indigo-600 mt-0.5">Salon HQ Loyalty Club</div>
        </div>
    </div>

    <!-- Appointment History Partial -->
    @include('clients.includes.client-history')
@endsection

