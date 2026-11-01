<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\Staff;

class AppointmentObserver
{
    /**
     * Handle the Appointment "updated" event.
     * When an appointment is marked as completed, update the assigned stylist's performance metrics.
     */
    public function updated(Appointment $appointment): void
    {
        if (($appointment->wasChanged('status') || $appointment->isDirty('status')) && $appointment->status === 'completed') {
            $staff = $appointment->staff ?? Staff::find($appointment->staff_id);

            if ($staff) {
                $staff->completed_works = ((int) $staff->completed_works) + 1;
                $staff->monthly_revenue = ((float) $staff->monthly_revenue) + ((float) $appointment->price);
                $staff->save();
            }
        }
    }
}
