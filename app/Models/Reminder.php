<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
protected $fillable = [
    'user_id', 'car_id', 'title', 'type', 'due_date',
    'is_recurring', 'is_mileage_based', 'mileage_interval', 'next_mileage_due',
    'status', 'notes'
];

protected $casts = [
    'due_date' => 'date',
    'is_recurring' => 'boolean',
    'is_mileage_based' => 'boolean',
];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function car(): BelongsTo { return $this->belongsTo(Car::class); }

    public function scopeActive($query) { return $query->where('status', 'active'); }
    public function scopeDueSoon($query, $days = 3) {
        return $query->active()->whereBetween('due_date', [now(), now()->addDays($days)]);
    }
}