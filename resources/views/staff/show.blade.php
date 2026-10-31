@extends('layouts.app')

@section('title', ($staff->user?->name ?? 'Staff') . ' - Profile & Works')

@section('header-left')
    <a href="{{ route('staff.index') }}" wire:navigate class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
        &larr; Back to Staff & Stylists
    </a>
@endsection

@section('content')
    <div x-data="{ activeTab: 'works', editOpen: {{ $errors->any() ? 'true' : 'false' }}, deleteOpen: false }">
        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600 font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Hero Profile Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 mb-6 grid grid-cols-1 lg:grid-cols-3 gap-8 shadow-2xs">
            <div class="lg:col-span-2 flex flex-col sm:flex-row items-start gap-6">
                <div class="w-22 h-22 rounded-3xl flex items-center justify-center text-white text-3xl font-bold shrink-0 shadow-lg" style="background: {{ $staff->avatar_gradient }}">
                    {{ $staff->initials }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h1 class="text-2xl font-bold text-slate-900">{{ $staff->user?->name }}</h1>
                            @include('includes.staff-badges', ['staff' => $staff])
                        </div>

                        @if (auth()->user()?->isOwner())
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="editOpen = true"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:border-indigo-600 hover:bg-indigo-50 text-xs font-semibold text-slate-700 hover:text-indigo-600 transition cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    <span>Edit Staff</span>
                                </button>

                                <button
                                    type="button"
                                    @click="deleteOpen = true"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-red-200 bg-red-50 hover:bg-red-600 text-xs font-semibold text-red-600 hover:text-white transition cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                        <path d="M10 11v6"></path>
                                        <path d="M14 11v6"></path>
                                    </svg>
                                    <span>Delete</span>
                                </button>
                            </div>
                        @endif
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
                <div class="text-2xl font-bold text-emerald-600 mt-1.5">{{ $staff->formatted_monthly_revenue }}</div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Commission / Retention</div>
                <div class="text-2xl font-bold text-indigo-600 mt-1.5">
                    {{ number_format($staff->commission_percentage, 1) }}%
                    <span class="text-xs font-medium text-slate-500">({{ $staff->repeat_clients }} repeat)</span>
                </div>
            </div>
        </div>

        <!-- Alpine.js Interactive Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 mb-6 overflow-x-auto">
            <button
                type="button"
                @click="activeTab = 'works'"
                :class="activeTab === 'works' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="px-4 py-2.5 border-b-2 text-sm font-bold whitespace-nowrap shrink-0 transition cursor-pointer"
            >
                Featured Works & Portfolio ({{ $staff->works->count() }})
            </button>
            <button
                type="button"
                @click="activeTab = 'schedule'"
                :class="activeTab === 'schedule' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="px-4 py-2.5 border-b-2 text-sm font-bold whitespace-nowrap shrink-0 transition cursor-pointer"
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
                                    <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-md">{{ $work->category?->name ?? 'Salon Service' }}</span>
                                    <span class="text-xs font-bold text-amber-600">★ {{ number_format($work->rating, 1) }}</span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 mb-1.5">{{ $work->title }}</h3>
                                <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $work->description }}</p>

                                <div class="mt-auto pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                    <div>
                                        <div><strong class="text-slate-700">Client:</strong> {{ $work->client_name }}</div>
                                        <div>{{ $work->performed_date }} • {{ $work->formatted_duration }} ({{ $work->duration_minutes }} mins)</div>
                                    </div>
                                    <div class="text-sm font-bold text-indigo-600">{{ $work->formatted_price }}</div>
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

        @if (auth()->user()?->isOwner())
            @include('staff.includes.profile-admin-modals')
        @endif
    </div>
@endsection
