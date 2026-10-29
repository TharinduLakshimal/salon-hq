<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'category_id',
        'job_title',
        'serves_gender',
        'availability_status',
        'experience',
        'joined_date',
        'commission_percentage',
        'rating',
        'reviews_count',
        'completed_works',
        'today_bookings',
        'monthly_revenue',
        'repeat_clients',
        'avatar_gradient',
        'bio',
        'specialties',
        'schedule',
    ];

    protected function casts(): array
    {
        return [
            'specialties' => 'array',
            'schedule' => 'array',
            'commission_percentage' => 'decimal:2',
            'rating' => 'float',
            'monthly_revenue' => 'decimal:2',
        ];
    }

    /**
     * The User record (role = 'worker') associated with this staff member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The department Category of this staff member.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Portfolio works performed by this staff member.
     */
    public function works(): HasMany
    {
        return $this->hasMany(StaffWork::class, 'staff_id')->latest();
    }

    /**
     * Recorded shifts for this staff member.
     */
    public function shifts(): HasMany
    {
        return $this->hasMany(StaffShift::class, 'staff_id')->orderByDesc('shift_date');
    }

    /**
     * Get 2-letter initials from the user's name.
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->user?->name ?? 'ST';
        $parts = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials ?: 'ST';
    }

    /**
     * Get formatted monthly revenue string (e.g., "Rs. 485,000").
     */
    public function getFormattedMonthlyRevenueAttribute(): string
    {
        return 'Rs. '.number_format((float) $this->monthly_revenue, 0);
    }

    /**
     * Get human-readable label for serves_gender.
     */
    public function getServesLabelAttribute(): string
    {
        return match ($this->serves_gender) {
            'female' => 'Female Only',
            'male' => 'Male Only',
            default => 'Both (Unisex)',
        };
    }

    /**
     * Get icon for serves_gender.
     */
    public function getServesIconAttribute(): string
    {
        return match ($this->serves_gender) {
            'female' => '♀',
            'male' => '♂',
            default => '⚥',
        };
    }

    /**
     * Get human-readable availability status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->availability_status) {
            'in_session' => 'In Session',
            'on_break' => 'On Break',
            'off_duty' => 'Off Duty',
            default => 'Available',
        };
    }

    /**
     * Get CSS status class suffix ('online', 'busy', 'break').
     */
    public function getStatusCssAttribute(): string
    {
        return match ($this->availability_status) {
            'in_session' => 'busy',
            'on_break', 'off_duty' => 'break',
            default => 'online',
        };
    }
}
