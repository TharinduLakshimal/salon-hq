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

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
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

            @if ($isOwner)
                <button
                    type="button"
                    wire:click="openModal"
                    class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 transition cursor-pointer shrink-0"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Add New Staff</span>
                </button>
            @endif
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
                @include('staff.includes.staff-card', ['member' => $member, 'isOwner' => $isOwner])
            @endforeach
        </div>
    @endif

    @if ($isOwner)
        @include('staff.includes.add-staff-modal')
        @include('staff.includes.edit-staff-modal')
        @include('staff.includes.delete-staff-modal')
    @endif
</div>
