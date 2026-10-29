<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-slate-900">Stylist Status</h2>
            <p class="text-[11px] text-slate-400">Click status badge to toggle live</p>
        </div>
        <a href="{{ route('staff.index') }}" wire:navigate class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Manage</a>
    </div>
    <div class="p-5 flex flex-col gap-4">
        @foreach ($staffMembers as $member)
            <div wire:key="dash-staff-{{ $member->id }}" class="flex items-center justify-between gap-3">
                <a href="{{ route('staff.show', $member->id) }}" wire:navigate class="flex items-center gap-3 min-w-0 group">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0" style="background: {{ $member->avatar_gradient }}">
                        {{ $member->initials }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-slate-900 group-hover:text-indigo-600 truncate">{{ $member->user?->name }}</div>
                        <div class="text-xs text-slate-500 truncate">{{ $member->job_title }}</div>
                    </div>
                </a>
                @include('includes.staff-badges', ['staff' => $member, 'type' => 'status-button'])
            </div>
        @endforeach
    </div>
</div>

