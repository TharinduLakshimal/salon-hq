@extends('layouts.app')

@section('title', 'My Profile - Stylist Workspace')

@section('header-left')
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl font-bold text-slate-900 truncate">My Stylist Profile</h1>
        <p class="hidden sm:block text-xs text-slate-500 mt-0.5 truncate">
            Manage your personal profile, consultation bio, and live chair availability.
        </p>
    </div>
@endsection

@section('header-right')
    <a
        href="{{ route('appointments.index') }}"
        wire:navigate
        class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs sm:text-sm font-semibold text-slate-700 shadow-2xs transition"
    >
        <svg class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <span>My Appointments</span>
    </a>
@endsection

@section('content')
    <div
        x-data="{
            currentStatus: '{{ $staff->availability_status }}',
            isUpdatingStatus: false,
            toastMessage: '{{ session('status') }}',
            toastErrorMessage: '',
            statusLabels: {
                available: 'Available',
                in_session: 'In Session',
                on_break: 'On Break',
                off_duty: 'Off Duty'
            },
            statusBadgeClasses: {
                available: 'bg-emerald-100 text-emerald-800 border-emerald-200',
                in_session: 'bg-amber-100 text-amber-800 border-amber-200',
                on_break: 'bg-blue-100 text-blue-800 border-blue-200',
                off_duty: 'bg-slate-100 text-slate-800 border-slate-200'
            },
            statusDotClasses: {
                available: 'bg-emerald-500',
                in_session: 'bg-amber-500 animate-pulse',
                on_break: 'bg-blue-500',
                off_duty: 'bg-slate-400'
            },
            async setAvailability(newStatus) {
                if (this.isUpdatingStatus || this.currentStatus === newStatus) {
                    return;
                }
                this.isUpdatingStatus = true;
                this.toastErrorMessage = '';

                try {
                    const response = await fetch('{{ route('worker.profile.availability') }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ availability_status: newStatus })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.currentStatus = data.status || newStatus;
                        this.toastMessage = data.message || `Availability changed to ${this.statusLabels[this.currentStatus]}.`;
                    } else {
                        const err = await response.json().catch(() => ({}));
                        this.toastErrorMessage = err.message || 'Could not update availability.';
                    }
                } catch (e) {
                    this.toastErrorMessage = 'Network error while updating availability.';
                } finally {
                    this.isUpdatingStatus = false;
                }
            }
        }"
        class="space-y-6"
    >
        <!-- Success Toast Notification -->
        <div
            x-show="toastMessage !== ''"
            x-transition
            class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-semibold text-emerald-800 flex items-center justify-between gap-3 shadow-2xs"
            style="display: none;"
        >
            <span class="inline-flex items-center gap-2 truncate">
                <span class="text-base">✓</span>
                <span x-text="toastMessage"></span>
            </span>
            <button type="button" @click="toastMessage = ''" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">✕</button>
        </div>

        <!-- Error Toast Notification -->
        <div
            x-show="toastErrorMessage !== ''"
            x-transition
            class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-semibold text-red-700 flex items-center justify-between gap-3 shadow-2xs"
            style="display: none;"
        >
            <span class="inline-flex items-center gap-2 truncate">
                <span class="text-base">⚠️</span>
                <span x-text="toastErrorMessage"></span>
            </span>
            <button type="button" @click="toastErrorMessage = ''" class="text-red-600 hover:text-red-900 cursor-pointer">✕</button>
        </div>

        @if ($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-semibold text-red-700 shadow-2xs">
                <div class="font-bold mb-1">Please fix the following errors:</div>
                <ul class="list-disc list-inside text-xs font-normal space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stylist Hero Header & Instant Availability Switcher -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 shadow-2xs grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">
            <div class="lg:col-span-2 flex flex-col sm:flex-row items-start gap-5">
                <div
                    class="w-20 h-20 sm:w-22 sm:h-22 rounded-3xl flex items-center justify-center text-white text-2xl sm:text-3xl font-bold shrink-0 shadow-lg shadow-indigo-600/20"
                    style="background: {{ $staff->avatar_gradient ?: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)' }}"
                >
                    {{ $staff->initials }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-2xl font-bold text-slate-900">{{ $staff->user?->name }}</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border" :class="statusBadgeClasses[currentStatus]">
                            <span class="w-2 h-2 rounded-full" :class="statusDotClasses[currentStatus]"></span>
                            <span x-text="statusLabels[currentStatus] || currentStatus"></span>
                        </span>
                    </div>

                    <div class="text-sm font-semibold text-indigo-600 mt-1 mb-2">
                        {{ $staff->job_title }} &bull; {{ $staff->category?->name ?? 'Hair Stylists' }}
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-3">
                        {{ $staff->bio ?: 'No consultation bio added yet. Tell clients about your experience and techniques below.' }}
                    </p>

                    <div class="flex flex-wrap gap-1.5">
                        @forelse ($staff->specialties ?? [] as $skill)
                            <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-semibold">{{ $skill }}</span>
                        @empty
                            <span class="text-xs text-slate-400 italic">No specialties listed</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Instant Availability Control Box -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Live Status Switcher</div>
                    <span x-show="isUpdatingStatus" class="text-[11px] text-indigo-600 font-medium" style="display: none;">Saving...</span>
                </div>
                <p class="text-xs text-slate-500">
                    Switch your chair status to let reception know if you are ready for walk-ins or currently occupied.
                </p>

                <div class="grid grid-cols-2 gap-2 mt-1">
                    <button
                        type="button"
                        :disabled="isUpdatingStatus"
                        @click="setAvailability('available')"
                        :class="currentStatus === 'available'
                            ? 'bg-emerald-600 text-white shadow-xs font-bold'
                            : 'bg-white text-slate-700 border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50'"
                        class="px-2.5 py-2 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Available</span>
                    </button>

                    <button
                        type="button"
                        :disabled="isUpdatingStatus"
                        @click="setAvailability('in_session')"
                        :class="currentStatus === 'in_session'
                            ? 'bg-amber-600 text-white shadow-xs font-bold'
                            : 'bg-white text-slate-700 border border-slate-200 hover:border-amber-300 hover:bg-amber-50'"
                        class="px-2.5 py-2 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>In Session</span>
                    </button>

                    <button
                        type="button"
                        :disabled="isUpdatingStatus"
                        @click="setAvailability('on_break')"
                        :class="currentStatus === 'on_break'
                            ? 'bg-blue-600 text-white shadow-xs font-bold'
                            : 'bg-white text-slate-700 border border-slate-200 hover:border-blue-300 hover:bg-blue-50'"
                        class="px-2.5 py-2 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span>On Break</span>
                    </button>

                    <button
                        type="button"
                        :disabled="isUpdatingStatus"
                        @click="setAvailability('off_duty')"
                        :class="currentStatus === 'off_duty'
                            ? 'bg-slate-800 text-white shadow-xs font-bold'
                            : 'bg-white text-slate-700 border border-slate-200 hover:border-slate-400 hover:bg-slate-100'"
                        class="px-2.5 py-2 rounded-xl text-xs transition cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span>Off Duty</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Read-Only Admin Metrics & Salon Settings -->
        <div>
            <div class="flex items-center justify-between mb-3 px-1">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Assigned Salon Profile &amp; Metrics</h2>
                    <p class="text-xs text-slate-500">Contracted commission, salon department, and verified ratings managed by Salon HQ owners.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Read-Only</span>
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Commission Rate</div>
                    <div class="text-2xl font-bold text-indigo-600 mt-1">
                        {{ number_format($staff->commission_percentage, 1) }}%
                    </div>
                    <div class="text-xs text-slate-500 mt-1">Contracted service share</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Job Title</div>
                    <div class="text-base font-bold text-slate-800 mt-1.5 truncate" title="{{ $staff->job_title }}">
                        {{ $staff->job_title }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">Seniority &amp; Position</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Department Category</div>
                    <div class="text-base font-bold text-slate-800 mt-1.5 truncate">
                        {{ $staff->category?->name ?? 'Hair Stylists' }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">Booking allocation category</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Client Rating</div>
                    <div class="text-2xl font-bold text-amber-600 mt-1">
                        ★ {{ number_format($staff->rating, 1) }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">{{ $staff->completed_works }} works &bull; {{ $staff->repeat_clients }} repeat</div>
                </div>
            </div>
        </div>

        <!-- Editable Profile Details Form -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 shadow-2xs">
            <div class="border-b border-slate-200 pb-4 mb-6">
                <h2 class="text-base font-bold text-slate-900">Edit Personal &amp; Consultation Info</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Update your contact number, client gender specialization, personal bio, and login credentials.
                </p>
            </div>

            <form action="{{ route('worker.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $staff->user?->name) }}"
                            required
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-xs font-semibold text-slate-700 mb-1.5">Mobile Phone Number *</label>
                        <input
                            type="text"
                            id="phone_number"
                            name="phone_number"
                            value="{{ old('phone_number', $staff->user?->phone_number) }}"
                            required
                            placeholder="077 123 4567"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                        >
                        @error('phone_number')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            value="{{ $staff->user?->email }}"
                            disabled
                            class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm text-slate-500 cursor-not-allowed focus:outline-none"
                        >
                        <p class="mt-1 text-[11px] text-slate-400">Account login email managed by salon administrator.</p>
                    </div>

                    <div>
                        <label for="gender" class="block text-xs font-semibold text-slate-700 mb-1.5">Gender *</label>
                        <select
                            id="gender"
                            name="gender"
                            required
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                        >
                            <option value="female" {{ old('gender', $staff->user?->gender) === 'female' ? 'selected' : '' }}>Female (♀)</option>
                            <option value="male" {{ old('gender', $staff->user?->gender) === 'male' ? 'selected' : '' }}>Male (♂)</option>
                            <option value="other" {{ old('gender', $staff->user?->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="specialties" class="block text-xs font-semibold text-slate-700 mb-1.5">Specialties &amp; Skills</label>
                        <input
                            type="text"
                            id="specialties"
                            name="specialties"
                            value="{{ old('specialties', is_array($staff->specialties) ? implode(', ', $staff->specialties) : '') }}"
                            placeholder="e.g. Keratin Treatments, Fade Cuts, Acrylic Nails, Bridal Updos"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                        >
                        <p class="mt-1 text-[11px] text-slate-400">Enter skills separated by commas.</p>
                        @error('specialties')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="bio" class="block text-xs font-semibold text-slate-700 mb-1.5">Consultation Bio</label>
                        <textarea
                            id="bio"
                            name="bio"
                            rows="3"
                            placeholder="Briefly describe your styling techniques, product expertise, and customer approach..."
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                        >{{ old('bio', $staff->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Change Section (Optional) -->
                <div class="pt-5 border-t border-slate-200">
                    <div class="mb-4">
                        <h3 class="text-sm font-bold text-slate-900">Change Password</h3>
                        <p class="text-xs text-slate-500">Leave blank if you do not want to change your current password.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">New Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                            >
                            @error('password')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                            >
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold shadow-sm shadow-indigo-600/20 transition cursor-pointer"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

