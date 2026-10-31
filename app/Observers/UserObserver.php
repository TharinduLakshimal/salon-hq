<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event by linking matching walk-in appointments.
     */
    public function created(User $user): void
    {
        if (blank($user->phone_number)) {
            return;
        }

        Appointment::query()
            ->whereNull('client_id')
            ->where('client_phone', $user->phone_number)
            ->update(['client_id' => $user->id]);
    }
}
