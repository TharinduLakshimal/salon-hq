<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'target_gender',
        'price',
        'duration_minutes',
        'is_package',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'duration_minutes' => 30,
        'is_package' => false,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
            'is_package' => 'boolean',
        ];
    }

    /**
     * Salon department category that owns this service.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get formatted LKR price string (e.g., "Rs. 10,000").
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rs. '.number_format((float) $this->price, 0);
    }
}
