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
            @php
                $statusBadge = match($member->availability_status) {
                    'in_session' => 'bg-blue-100 text-blue-700 hover:bg-blue-200',
                    'on_break', 'off_duty' => 'bg-amber-100 text-amber-700 hover:bg-amber-200',
                    default => 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200',
                };
            @endphp
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
                <button
                    type="button"
                    wire:click="cycleStatus({{ $member->id }})"
                    title="Click to cycle status"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 transition cursor-pointer {{ $statusBadge }}"
                >
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ $member->status_label }}
                </button>
            </div>
        @endforeach
    </div>
</div>

