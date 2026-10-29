@php
    $badgeType = $type ?? 'both';

    $statusBadge = match ($staff->availability_status) {
        'in_session' => $badgeType === 'status-button' ? 'bg-blue-100 text-blue-700 hover:bg-blue-200' : 'bg-blue-100 text-blue-700',
        'on_break', 'off_duty' => $badgeType === 'status-button' ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-amber-100 text-amber-700',
        default => $badgeType === 'status-button' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-emerald-100 text-emerald-700',
    };

    $servesBadge = match ($staff->serves_gender) {
        'female' => 'bg-pink-100 text-pink-700 border-pink-200',
        'male' => 'bg-sky-100 text-sky-700 border-sky-200',
        default => 'bg-purple-100 text-purple-700 border-purple-200',
    };
@endphp

@if ($badgeType === 'status-button')
    <button
        type="button"
        wire:click="cycleStatus({{ $staff->id }})"
        title="Click to cycle status"
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 transition cursor-pointer {{ $statusBadge }}"
    >
        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
        {{ $staff->status_label }}
    </button>
@endif

@if (in_array($badgeType, ['both', 'status'], true))
    <span class="inline-flex items-center gap-1.5 {{ $statusClass ?? ($badgeType === 'both' ? 'px-3 py-1' : 'px-2.5 py-0.5 shrink-0') }} rounded-full text-xs font-semibold {{ $statusBadge }}">
        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
        {{ $staff->status_label }}
    </span>
@endif

@if (in_array($badgeType, ['both', 'gender'], true))
    <span class="inline-flex items-center gap-1 {{ $genderClass ?? ($badgeType === 'both' ? 'px-2.5 py-1 rounded-full' : 'px-2 py-0.5 rounded-md') }} text-xs font-bold border {{ $servesBadge }}">
        {{ $staff->serves_icon }} {{ $staff->serves_label }}
    </span>
@endif

