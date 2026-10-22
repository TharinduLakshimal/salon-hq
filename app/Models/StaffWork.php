<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffWork extends Model
{
    protected $table = 'staff_works';

    protected $fillable = [
        'staff_id',
        'title',
        'category',
        'client_name',
        'performed_date',
        'duration',
        'price',
        'rating',
        'accent_color',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}

