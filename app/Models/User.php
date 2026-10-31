<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone_number', 'gender', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Strip spaces, dashes, and parentheses from phone_number before saving.
     */
    protected function phoneNumber(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => $value !== null
                ? preg_replace('/[\s\-()]+/', '', $value)
                : null,
        );
    }

    /**
     * Check whether this user is the main salon admin (owner).
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check whether this user is a salon worker / stylist.
     */
    public function isWorker(): bool
    {
        return $this->role === 'worker';
    }

    /**
     * Staff profile if this user has role = 'worker'.
     */
    public function staffProfile(): HasOne
    {
        return $this->hasOne(Staff::class, 'user_id');
    }

    /**
     * Salon appointments booked under this client user account.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }
}
