<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'category_id');
    }

    public function works(): HasMany
    {
        return $this->hasMany(StaffWork::class, 'category_id');
    }
}
