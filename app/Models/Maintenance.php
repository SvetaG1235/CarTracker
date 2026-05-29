<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = ['car_id', 'service_type', 'part_name', 'date', 'mileage_at', 'next_mileage', 'next_due_date', 'notes'];

    protected $casts = [
        'date' => 'date',
        'mileage_at' => 'integer',
        'next_mileage' => 'integer',
        'next_due_date' => 'date',
    ];

    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
}