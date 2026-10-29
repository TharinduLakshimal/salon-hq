<div @keydown.escape.window="$wire.closeModal()">
    @if ($successMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-semibold text-emerald-800 flex items-center justify-between"
        >
            <span>✅ {{ $successMessage }}</span>
            <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- Department Filter & Live Search Toolbar -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <div class="flex flex-wrap items-center gap-2">
            @php
                $categories = [
                    'all' => "All Staff ($totalCount)",
                    'Bridal & Makeup' => 'Bridal & Makeup',
                    'Hair Stylists' => 'Hair Stylists',
                    'Barbers' => 'Barbers',
                    'Estheticians & Nails' => 'Estheticians & Nails',
                ];
            @endphp

            @foreach ($categories as $key => $label)
                <button
                    type="button"
                    wire:click="setCategory('{{ $key }}')"
                    class="px-4 py-2 rounded-full text-xs font-semibold border transition cursor-pointer {{ $activeCategory === $key ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-72">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Live search by name, role, skill..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-white text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition"
                >
            </div>

            <button
                type="button"
                wire:click="openModal"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 transition cursor-pointer shrink-0"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add New Staff</span>
            </button>
        </div>
    </div>

    <!-- Unisex Gender Filter Bar -->
    <div class="flex flex-wrap items-center gap-2 pb-5 mb-6 border-b border-slate-200">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mr-1">Serves Clients:</span>
        @php
            $genders = [
                'all' => 'All Types',
                'both' => '⚥ Both (Unisex)',
                'female' => '♀ Female Only',
                'male' => '♂ Male Only',
            ];
        @endphp

        @foreach ($genders as $gKey => $gLabel)
            <button
                type="button"
                wire:click="setGender('{{ $gKey }}')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border transition cursor-pointer {{ $activeGender === $gKey ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}"
            >
                {{ $gLabel }}
            </button>
        @endforeach
    </div>

    <!-- Staff Cards Grid -->
    @if ($staffMembers->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center text-sm text-slate-500">
            No staff members match your current filters.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($staffMembers as $member)
                @php
                    $skills = $member->specialties ?? [];
                @endphp
                <a
                    href="{{ route('staff.show', $member->id) }}"
                    wire:navigate
                    wire:key="staff-card-{{ $member->id }}"
                    class="group bg-white border border-slate-200 rounded-2xl overflow-hidden flex flex-col shadow-2xs hover:-translate-y-1 hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-500/10 transition duration-200"
                >
                    <div class="p-6 pb-4 flex items-start gap-4">
                        <div class="w-15 h-15 rounded-2xl flex items-center justify-center text-white text-xl font-bold shrink-0 shadow-md" style="background: {{ $member->avatar_gradient }}">
                            {{ $member->initials }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h3 class="text-base font-bold text-slate-900 truncate">{{ $member->user?->name }}</h3>
                                @include('includes.staff-badges', ['staff' => $member, 'type' => 'status'])
                            </div>

                            <div class="text-xs font-semibold text-indigo-600 mb-2">{{ $member->job_title }}</div>

                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-1 text-xs text-slate-500">
                                    <span class="text-amber-500 font-bold">★</span>
                                    <span class="font-bold text-slate-900">{{ number_format($member->rating, 1) }}</span>
                                    <span>({{ $member->reviews_count }})</span>
                                </div>

                                @include('includes.staff-badges', ['staff' => $member, 'type' => 'gender'])
                            </div>
                        </div>
                    </div>

                    <div class="px-6 pb-4 flex flex-wrap gap-1.5">
                        @foreach (array_slice($skills, 0, 3) as $skill)
                            <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-md">{{ $skill }}</span>
                        @endforeach
                        @if (count($skills) > 3)
                            <span class="bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-md">+{{ count($skills) - 3 }} more</span>
                        @endif
                    </div>

                    <div class="mx-6 mb-5 p-3 bg-slate-50 border border-slate-100 rounded-xl grid grid-cols-3 text-center divide-x divide-slate-200">
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $member->experience }}</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Experience</div>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $member->completed_works }}+</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Works Done</div>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $member->today_bookings }}</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Today's Jobs</div>
                        </div>
                    </div>

                    <div class="mt-auto px-6 py-3.5 border-t border-slate-200 bg-slate-50/50 group-hover:bg-indigo-50/60 flex items-center justify-between text-xs font-semibold text-slate-500 group-hover:text-indigo-600 transition">
                        <span>📞 {{ $member->user?->phone_number ?? 'N/A' }}</span>
                        <span>View Profile & Works &rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <!-- Livewire + Alpine.js Add New Staff Modal -->
    @if ($showAddModal)
        <div
            wire:click.self="closeModal"
            class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900">Add New Staff Member</h2>
                    <button type="button" wire:click="closeModal" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer">✕</button>
                </div>

                <form wire:submit="saveStaff" class="flex flex-col overflow-hidden">
                    <div class="p-6 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name *</label>
                            <input type="text" wire:model="name" placeholder="e.g. Oshadi Perera" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Job Title / Role *</label>
                            <input type="text" wire:model="job_title" placeholder="e.g. Senior Hair Stylist" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            @error('job_title') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Works For (Client Gender) *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="serves_gender" value="both" class="peer sr-only">
                                    <div class="rounded-xl border-2 border-slate-200 p-3 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                        <div class="text-xs font-bold">⚥ Both (Unisex)</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">Male & Female Clients</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="serves_gender" value="female" class="peer sr-only">
                                    <div class="rounded-xl border-2 border-slate-200 p-3 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                        <div class="text-xs font-bold">♀ Female Only</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">Ladies' Salon & Bridal</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="serves_gender" value="male" class="peer sr-only">
                                    <div class="rounded-xl border-2 border-slate-200 p-3 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                        <div class="text-xs font-bold">♂ Male Only</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">Gents' Barber & Grooming</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Department Category *</label>
                            <select wire:model="category" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                                <option value="Hair Stylists">Hair Stylists</option>
                                <option value="Bridal & Makeup">Bridal & Makeup</option>
                                <option value="Barbers">Barbers</option>
                                <option value="Estheticians & Nails">Estheticians & Nails</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Staff Member's Gender *</label>
                            <select wire:model="gender" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Experience *</label>
                            <input type="text" wire:model="experience" placeholder="e.g. 4 Years" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            @error('experience') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number *</label>
                            <input type="text" wire:model="phone_number" placeholder="e.g. 077 123 4567" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            @error('phone_number') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Login Email Address * <span class="font-normal text-slate-400">(Default password: <code>password</code>)</span>
                            </label>
                            <input type="email" wire:model="email" placeholder="e.g. oshadi@salonhq.com" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Specialties / Skills (comma separated)</label>
                            <input type="text" wire:model="specialties" placeholder="e.g. Balayage, Haircut, Keratin, Bridal Dressing" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Short Bio & Experience Summary</label>
                            <textarea wire:model="bio" rows="3" placeholder="Write a short introduction..." class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-sm cursor-pointer">
                            <span wire:loading.remove wire:target="saveStaff">Save Staff Member</span>
                            <span wire:loading wire:target="saveStaff">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

