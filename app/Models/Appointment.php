<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'client_id',
        'staff_id',
        'category_id',
        'client_name',
        'client_phone',
        'client_gender',
        'booking_type',
        'service_name',
        'duration_minutes',
        'appointment_date',
        'start_time',
        'end_time',
        'price',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'duration_minutes' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Strip spaces, dashes, and parentheses from client_phone before saving.
     */
    protected function clientPhone(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => $value !== null
                ? preg_replace('/[\s\-()]+/', '', $value)
                : null,
        );
    }

    /**
     * Registered client User account (null when booked for a walk-in guest).
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Assigned salon stylist or barber.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Salon department category for the service.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the client's display name from the related User when client_id is set, or client_name for walk-in guests.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->client_id !== null && $this->client !== null) {
            return $this->client->name;
        }

        return (string) $this->client_name;
    }

    /**
     * Get human-readable booking source label matching the Appointments UI.
     */
    public function getBookingTypeLabelAttribute(): string
    {
        return match ($this->booking_type) {
            'online_booking' => 'Online Booking',
            'phone_desk' => 'Phone Desk',
            default => 'Walk-In',
        };
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'In Progress',
            'confirmed' => 'Confirmed',
            'pending' => 'Pending',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Get formatted 12-hour start time (e.g., "09:30 AM").
     */
    public function getFormattedStartTimeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('h:i A');
    }

    /**
     * Get formatted 12-hour end time (e.g., "01:00 PM").
     */
    public function getFormattedEndTimeAttribute(): string
    {
        return Carbon::parse($this->end_time)->format('h:i A');
    }

    /**
     * Get hourly slot key used by the Chair Schedule timeline (e.g., "09:00 AM").
     */
    public function getHourKeyAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('h:00 A');
    }

    /**
     * Get formatted LKR price string (e.g., "Rs. 18,500").
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rs. '.number_format((float) $this->price, 0);
    }
}
