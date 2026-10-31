<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isOwner() || $user->isWorker();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->isOwner()) {
            return true;
        }

        if ($user->isWorker()) {
            $workerStaff = $user->staffProfile;

            return $workerStaff !== null && $appointment->staff_id === $workerStaff->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOwner() || $user->isWorker();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->isOwner()) {
            return true;
        }

        if ($user->isWorker()) {
            $workerStaff = $user->staffProfile;

            return $workerStaff !== null && $appointment->staff_id === $workerStaff->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->isOwner();
    }
}
