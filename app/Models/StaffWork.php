<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffWork extends Model
{
    protected $table = 'staff_works';

    protected $fillable = [
        'staff_id',
        'category_id',
        'title',
        'client_name',
        'performed_date',
        'duration_minutes',
        'price',
        'rating',
        'accent_color',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'price' => 'decimal:2',
            'rating' => 'float',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get formatted price string (e.g., "Rs. 65,000").
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rs. '.number_format((float) $this->price, 0);
    }

    /**
     * Get human-readable duration from duration_minutes (e.g., "3h 30m").
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = (int) $this->duration_minutes;
        if ($minutes <= 0) {
            return 'N/A';
        }
        $hours = intdiv($minutes, 60);
        $rem = $minutes % 60;

        if ($hours > 0 && $rem > 0) {
            return "{$hours}h {$rem}m";
        }
        if ($hours > 0) {
            return "{$hours}h 00m";
        }

        return "{$rem}m";
    }
}
