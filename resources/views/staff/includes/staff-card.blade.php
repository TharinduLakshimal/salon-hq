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

