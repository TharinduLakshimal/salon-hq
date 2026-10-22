<?php

namespace App\Livewire;

use App\Models\Staff;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class StylistStatusWidget extends Component
{
    /**
     * Cycle a staff member's availability status with 1 click in MySQL.
     */
    public function cycleStatus(int $staffId): void
    {
        $staff = Staff::findOrFail($staffId);

        $nextStatus = match ($staff->availability_status) {
            'available' => 'in_session',
            'in_session' => 'on_break',
            default => 'available',
        };

        $staff->update(['availability_status' => $nextStatus]);
    }

    public function render(): View
    {
        $staffMembers = Staff::with('user')->take(5)->get();

        return view('livewire.stylist-status-widget', compact('staffMembers'));
    }
}

