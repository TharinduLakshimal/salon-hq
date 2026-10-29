@php
    $skills = $member->specialties ?? [];
@endphp
<div
    wire:key="staff-card-{{ $member->id }}"
    class="group bg-white border border-slate-200 rounded-2xl overflow-hidden flex flex-col shadow-2xs hover:-translate-y-1 hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-500/10 transition duration-200"
>
    <a href="{{ route('staff.show', $member->id) }}" wire:navigate class="flex-1 flex flex-col">
        <div class="p-4 sm:p-6 pb-4 flex items-start gap-3.5 sm:gap-4">
            <div class="w-14 h-14 sm:w-15 sm:h-15 rounded-2xl flex items-center justify-center text-white text-lg sm:text-xl font-bold shrink-0 shadow-md" style="background: {{ $member->avatar_gradient }}">
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

        <div class="px-4 sm:px-6 pb-4 flex flex-wrap gap-1.5">
            @foreach (array_slice($skills, 0, 3) as $skill)
                <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-md">{{ $skill }}</span>
            @endforeach
            @if (count($skills) > 3)
                <span class="bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-md">+{{ count($skills) - 3 }} more</span>
            @endif
        </div>

        <div class="mx-4 sm:mx-6 mb-5 p-3 bg-slate-50 border border-slate-100 rounded-xl grid grid-cols-3 text-center divide-x divide-slate-200">
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
    </a>

    <div class="mt-auto px-4 sm:px-6 py-3.5 border-t border-slate-200 bg-slate-50/50 group-hover:bg-indigo-50/60 flex flex-wrap items-center justify-between gap-2.5 text-xs font-semibold text-slate-500 transition">
        <a href="{{ route('staff.show', $member->id) }}" wire:navigate class="hover:text-indigo-600 whitespace-nowrap">
            📞 {{ $member->user?->phone_number ?? 'N/A' }} &bull; View Profile &rarr;
        </a>

        @if ($isOwner ?? false)
            <div class="flex items-center gap-1.5 ml-auto">
                <button
                    type="button"
                    wire:click="editStaff({{ $member->id }})"
                    title="Edit {{ $member->user?->name }}"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit</span>
                </button>

                <button
                    type="button"
                    wire:click="confirmDelete({{ $member->id }})"
                    title="Delete {{ $member->user?->name }}"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-red-600 hover:border-red-500 hover:bg-red-50 transition cursor-pointer"
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
</div>
