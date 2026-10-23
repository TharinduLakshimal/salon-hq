@extends('layouts.app')

@section('title', ($staff->user?->name ?? 'Staff') . ' - Profile & Works')

@section('header-left')
    <a href="{{ route('staff.index') }}" wire:navigate class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
        &larr; Back to Staff & Stylists
    </a>
@endsection

@section('content')
    @php
        $statusBadge = match($staff->availability_status) {
            'in_session' => 'bg-blue-100 text-blue-700',
            'on_break', 'off_duty' => 'bg-amber-100 text-amber-700',
            default => 'bg-emerald-100 text-emerald-700',
        };
        $servesBadge = match($staff->serves_gender) {
            'female' => 'bg-pink-100 text-pink-700 border-pink-200',
            'male' => 'bg-sky-100 text-sky-700 border-sky-200',
            default => 'bg-purple-100 text-purple-700 border-purple-200',
        };
    @endphp

    <div x-data="{ activeTab: 'works' }">
        <!-- Hero Profile Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 mb-6 grid grid-cols-1 lg:grid-cols-3 gap-8 shadow-2xs">
            <div class="lg:col-span-2 flex flex-col sm:flex-row items-start gap-6">
                <div class="w-22 h-22 rounded-3xl flex items-center justify-center text-white text-3xl font-bold shrink-0 shadow-lg" style="background: {{ $staff->avatar_gradient }}">
                    {{ $staff->initials }}
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-2xl font-bold text-slate-900">{{ $staff->user?->name }}</h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $staff->status_label }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $servesBadge }}">
                            {{ $staff->serves_icon }} {{ $staff->serves_label }}
                        </span>
                    </div>

                    <div class="text-sm font-semibold text-indigo-600 mt-1 mb-3">{{ $staff->job_title }}</div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ $staff->bio }}</p>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($staff->specialties ?? [] as $skill)
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg text-xs font-semibold">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact & Schedule Box -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex flex-col gap-3 text-xs">
                <div class="flex justify-between pb-2.5 border-b border-dashed border-slate-200">
                    <span class="text-slate-500 font-medium">Serves Clients</span>
                    <span class="font-semibold text-slate-900">{{ $staff->serves_icon }} {{ $staff->serves_label }}</span>
                </div>
                <div class="flex justify-between pb-2.5 border-b border-dashed border-slate-200">
                    <span class="text-slate-500 font-medium">Phone</span>
                    <span class="font-semibold text-slate-900">{{ $staff->user?->phone_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between pb-2.5 border-b border-dashed border-slate-200">
                    <span class="text-slate-500 font-medium">Email</span>
                    <span class="font-semibold text-slate-900">{{ $staff->user?->email }}</span>
                </div>
                <div class="flex justify-between pb-2.5 border-b border-dashed border-slate-200">
                    <span class="text-slate-500 font-medium">Experience</span>
                    <span class="font-semibold text-slate-900">{{ $staff->experience }} (Joined {{ $staff->joined_date }})</span>
                </div>
                @foreach ($staff->schedule ?? [] as $days => $hours)
                    <div class="flex justify-between pb-2.5 last:pb-0 border-b last:border-b-0 border-dashed border-slate-200">
                        <span class="text-slate-500 font-medium">{{ $days }}</span>
                        <span class="font-semibold text-slate-900">{{ $hours }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Stylist Performance Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Works Completed</div>
                <div class="text-2xl font-bold text-slate-900 mt-1.5">{{ $staff->completed_works }}+</div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Client Rating</div>
                <div class="text-2xl font-bold text-amber-600 mt-1.5">
                    ★ {{ number_format($staff->rating, 1) }}
                    <span class="text-xs font-medium text-slate-500">({{ $staff->reviews_count }} reviews)</span>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Monthly Revenue</div>
                <div class="text-2xl font-bold text-emerald-600 mt-1.5">{{ $staff->monthly_revenue }}</div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Repeat Client Rate</div>
                <div class="text-2xl font-bold text-indigo-600 mt-1.5">{{ $staff->repeat_clients }}</div>
            </div>
        </div>

        <!-- Alpine.js Interactive Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 mb-6">
            <button
                type="button"
                @click="activeTab = 'works'"
                :class="activeTab === 'works' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="px-4 py-2.5 border-b-2 text-sm font-bold transition cursor-pointer"
            >
                Featured Works & Portfolio ({{ $staff->works->count() }})
            </button>
            <button
                type="button"
                @click="activeTab = 'schedule'"
                :class="activeTab === 'schedule' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="px-4 py-2.5 border-b-2 text-sm font-bold transition cursor-pointer"
            >
                Shift Schedule & Specialties
            </button>
        </div>

        <!-- Tab 1: Portfolio Works -->
        <div x-show="activeTab === 'works'">
            @if ($staff->works->isEmpty())
                <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-sm text-slate-500">
                    No portfolio works recorded for this staff member yet.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach ($staff->works as $work)
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-lg transition">
                            <div class="h-2 w-full" style="background: {{ $work->accent_color }}"></div>
                            <div class="p-5 flex-1 flex flex-col">
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-md">{{ $work->category }}</span>
                                    <span class="text-xs font-bold text-amber-600">★ {{ number_format($work->rating, 1) }}</span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 mb-1.5">{{ $work->title }}</h3>
                                <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $work->description }}</p>

                                <div class="mt-auto pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                    <div>
                                        <div><strong class="text-slate-700">Client:</strong> {{ $work->client_name }}</div>
                                        <div>{{ $work->performed_date }} • {{ $work->duration }}</div>
                                    </div>
                                    <div class="text-sm font-bold text-indigo-600">{{ $work->price }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tab 2: Schedule & Specialties -->
        <div x-show="activeTab === 'schedule'" style="display: none;" class="bg-white border border-slate-200 rounded-2xl p-6">
            <h3 class="text-base font-bold text-slate-900 mb-4">Weekly Working Hours</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($staff->schedule ?? [] as $days => $hours)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-700">{{ $days }}</span>
                        <span class="text-sm font-bold text-indigo-600">{{ $hours }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
