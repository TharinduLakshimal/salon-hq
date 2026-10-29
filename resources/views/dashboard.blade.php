@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header-left')
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl font-bold text-slate-900 truncate">Dashboard Overview</h1>
        <p class="hidden sm:block text-xs text-slate-500 mt-0.5 truncate">Welcome back, {{ auth()->user()->name ?? 'Admin' }} — Here is what's
            happening at your salon today.</p>
    </div>
@endsection

@section('header-right')
    <div class="relative hidden lg:block">
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" placeholder="Search bookings, clients..."
            class="pl-9 pr-4 py-2 w-64 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition">
    </div>

    <a href="{{ route('appointments.index', ['new' => 1]) }}" wire:navigate
        class="inline-flex items-center gap-1.5 sm:gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 shrink-0 whitespace-nowrap transition">
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>New Appointment</span>
    </a>
@endsection

@section('content')
    @include('dashboard.includes.kpi-cards')

    <!-- Main Grid: Appointments Table + Side Widgets -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @include('dashboard.includes.today-appointments')

        <!-- Right Column: Quick Actions & Livewire Stylist Status -->
        <div class="flex flex-col gap-6">
            @include('dashboard.includes.quick-actions')

            <!-- Livewire Stylist Status Component -->
            <livewire:stylist-status-widget />
        </div>
    </div>
@endsection
